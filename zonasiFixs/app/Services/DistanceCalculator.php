<?php
/**
 * Distance Calculator Service
 * Implements Haversine Formula for accurate distance calculation
 * between two geographic coordinates
 */

class DistanceCalculator {
    
    /**
     * Calculate distance between two coordinates using Haversine formula
     * 
     * @param float $lat1 Starting latitude
     * @param float $lon1 Starting longitude
     * @param float $lat2 Ending latitude
     * @param float $lon2 Ending longitude
     * @return float Distance in kilometers (rounded to 2 decimals)
     */
    public static function calculate($lat1, $lon1, $lat2, $lon2) {
        // Check if coordinates are valid
        if (!self::isValidCoordinate($lat1, $lon1) || !self::isValidCoordinate($lat2, $lon2)) {
            return null;
        }
        
        $earthRadius = 6371; // Earth's radius in kilometers
        
        // Convert degrees to radians
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        // Haversine formula
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        $distance = $earthRadius * $c;
        
        return round($distance, 2);
    }
    
    /**
     * Validate coordinates
     * 
     * @param float $lat Latitude (-90 to 90)
     * @param float $lon Longitude (-180 to 180)
     * @return bool
     */
    private static function isValidCoordinate($lat, $lon) {
        return is_numeric($lat) && is_numeric($lon) &&
               $lat >= -90 && $lat <= 90 &&
               $lon >= -180 && $lon <= 180;
    }
    
    /**
     * Calculate distance for a registration record
     * Updates the jarak_km column automatically
     * 
     * @param PDO $db Database connection
     * @param int $pendaftaranId Registration ID
     * @return float|null Distance in km or null if calculation fails
     */
    public static function calculateForRegistration($db, $pendaftaranId) {
        try {
            // Get coordinates from registration and school
            $stmt = $db->prepare("
                SELECT 
                    p.latitude_siswa, 
                    p.longitude_siswa,
                    s.latitude as latitude_sekolah, 
                    s.longitude as longitude_sekolah
                FROM pendaftaran p
                JOIN sekolah s ON p.sekolah_id = s.id
                WHERE p.id = ?
            ");
            $stmt->execute([$pendaftaranId]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$data) {
                return null;
            }
            
            // Calculate distance
            $distance = self::calculate(
                $data['latitude_siswa'],
                $data['longitude_siswa'],
                $data['latitude_sekolah'],
                $data['longitude_sekolah']
            );
            
            if ($distance !== null) {
                // Update the distance in database
                $stmt = $db->prepare("UPDATE pendaftaran SET jarak_km = ? WHERE id = ?");
                $stmt->execute([$distance, $pendaftaranId]);
            }
            
            return $distance;
            
        } catch (Exception $e) {
            error_log("Distance calculation failed: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Batch calculate distances for all pending registrations
     * 
     * @param PDO $db Database connection
     * @return int Number of records updated
     */
    public static function batchCalculate($db) {
        try {
            $stmt = $db->query("
                SELECT p.id, p.latitude_siswa, p.longitude_siswa,
                       s.latitude as latitude_sekolah, s.longitude as longitude_sekolah
                FROM pendaftaran p
                JOIN sekolah s ON p.sekolah_id = s.id
                WHERE (p.jarak_km IS NULL OR p.jarak_km = 0)
                  AND p.latitude_siswa IS NOT NULL
                  AND p.longitude_siswa IS NOT NULL
                  AND s.latitude IS NOT NULL
                  AND s.longitude IS NOT NULL
            ");
            
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $updated = 0;
            
            foreach ($records as $record) {
                $distance = self::calculate(
                    $record['latitude_siswa'],
                    $record['longitude_siswa'],
                    $record['latitude_sekolah'],
                    $record['longitude_sekolah']
                );
                
                if ($distance !== null) {
                    $stmt = $db->prepare("UPDATE pendaftaran SET jarak_km = ? WHERE id = ?");
                    $stmt->execute([$distance, $record['id']]);
                    $updated++;
                }
            }
            
            return $updated;
            
        } catch (Exception $e) {
            error_log("Batch distance calculation failed: " . $e->getMessage());
            return 0;
        }
    }
}
