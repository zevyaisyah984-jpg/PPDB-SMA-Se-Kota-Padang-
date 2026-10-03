<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Ditutup - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }
        
        /* Background Decorations */
        .bg-decoration {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .blue-arc {
            position: absolute;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 150%;
            height: 60%;
            background-color: #3b82f6;
            border-radius: 50% 50% 0 0;
            opacity: 0.1;
        }

        .student-img {
            position: absolute;
            bottom: 0;
            max-width: 350px;
            z-index: 0;
            opacity: 0.8;
            transition: all 0.5s ease;
        }
        .student-left { left: 5%; }
        .student-right { right: 5%; }

        .closed-card {
            background: white;
            padding: 3.5rem 2.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 650px;
            width: 90%;
            z-index: 10;
            position: relative;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .closed-card h1 {
            color: #1e3a8a;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.3;
            font-size: 2.25rem;
        }

        .closed-card p {
            color: #4b5563;
            font-size: 1.125rem;
            margin-bottom: 2rem;
        }

        .btn-home {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            border-radius: 50px;
            padding: 0.8rem 2.5rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .student-img { display: none; }
            .closed-card { padding: 2.5rem 1.5rem; }
            .closed-card h1 { font-size: 1.75rem; }
        }
    </style>
</head>
<body>
    <div class="bg-decoration">
        <div class="blue-arc"></div>
    </div>

    <!-- Reusing generated illustration logic or icons -->
    <img src="https://img.freepik.com/free-vector/students-watching-webinar-online_74855-6351.jpg" alt="Student Left" class="student-img student-left" onerror="this.style.display='none'">
    <img src="https://img.freepik.com/free-vector/online-education-concept-illustration_114360-117.jpg" alt="Student Right" class="student-img student-right" onerror="this.style.display='none'">

    <div class="closed-card animate__animated animate__zoomIn">
        <h1>Pendaftaran / Pembuatan Akun PPDB Online<br><span class="text-primary"><?php echo APP_NAME; ?></span><br>sudah ditutup</h1>
        <p>Mohon maaf, batas waktu pendaftaran dan pembuatan akun baru telah berakhir. Silakan pantau terus informasi selanjutnya di portal resmi kami.</p>
        <div class="d-flex flex-column gap-2 align-items-center">
            <a href="<?php echo url('/'); ?>" class="btn-home">Kembali ke Beranda</a>
            <small class="text-muted mt-3">Sudah punya akun? <a href="<?php echo url('/login'); ?>" class="text-primary text-decoration-none fw-bold">Login di sini</a></small>
        </div>
    </div>

    <!-- Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</body>
</html>
