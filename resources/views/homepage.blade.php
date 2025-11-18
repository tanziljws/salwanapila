@extends('layouts.public')

@section('content')
<style>
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1d4ed8;
        --primary-light: #3b82f6;
        --secondary-color: #64748b;
        --accent-color: #7c3aed;
        --success-color: #059669;
        --warning-color: #d97706;
        --danger-color: #dc2626;
        --light-color: #f8fafc;
        --dark-color: #0f172a;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --border-radius: 12px;
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        
        /* Typography */
        --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        --font-heading: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        
        /* Spacing Scale */
        --space-xs: 0.25rem;
        --space-sm: 0.5rem;
        --space-md: 1rem;
        --space-lg: 1.5rem;
        --space-xl: 2rem;
        --space-2xl: 3rem;
        --space-3xl: 4rem;
    }

    .public-homepage {
        background: var(--light-color);
        min-height: 100vh;
        font-family: var(--font-primary);
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Typography Hierarchy */
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading);
        font-weight: 600;
        line-height: 1.2;
        color: var(--dark-color);
        margin-bottom: var(--space-md);
    }
    
    h1 { font-size: 2.5rem; font-weight: 700; }
    h2 { font-size: 2rem; font-weight: 600; }
    h3 { font-size: 1.75rem; font-weight: 600; }
    h4 { font-size: 1.5rem; font-weight: 500; }
    h5 { font-size: 1.25rem; font-weight: 500; }
    h6 { font-size: 1.125rem; font-weight: 500; }
    
    p {
        margin-bottom: var(--space-md);
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* Header Section */
    .header-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        transition: var(--transition);
    }

    .header-section.scrolled {
        background: rgba(255, 255, 255, 0.98);
        box-shadow: var(--shadow);
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-decoration: none;
        color: var(--primary-dark);
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 1rem;
        transition: var(--transition);
        letter-spacing: -0.025em;
    }

    .navbar-brand:hover {
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .school-logo {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        background: #ffffff;
        padding: 4px;
        border: 2px solid #e0e0e0;
    }

    .school-logo:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: var(--shadow-hover);
    }

    .logo-image {
        width: 100%;
        height: 100%;
        object-fit: contain; /* keep full logo visible without cropping */
        transition: var(--transition);
        display: block;
    }

    .school-name h1 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-dark);
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        font-family: var(--font-heading);
        letter-spacing: -0.025em;
        line-height: 1.2;
    }

    .school-name p {
        margin: 0;
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 500;
        font-family: var(--font-primary);
    }

    .nav-link {
        color: var(--text-primary) !important;
        font-weight: 600;
        font-family: var(--font-primary);
        padding: 0.4rem 0.8rem !important;
        border-radius: var(--border-radius);
        transition: var(--transition);
        position: relative;
        font-size: 0.85rem;
    }

    .nav-link:hover {
        color: var(--primary-color) !important;
        background: rgba(0, 102, 204, 0.1);
        transform: translateY(-2px);
    }

    /* Dropdown Styles */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle {
        cursor: pointer;
        user-select: none;
    }

    .dropdown-toggle::after {
        content: '';
        display: inline-block;
        margin-left: 0.5rem;
        vertical-align: 0.255em;
        border-top: 0.3em solid var(--primary-dark);
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
        transition: var(--transition);
    }

    .dropdown:hover .dropdown-toggle::after {
        border-top-color: var(--primary-color);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        min-width: 200px;
        padding: 0.5rem 0;
        margin: 0;
        background-color: white;
        border: 1px solid rgba(0,0,0,0.15);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        list-style: none;
    }

    .dropdown-menu.show {
        display: block !important;
    }

    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.75rem 1.5rem;
        clear: both;
        font-weight: 500;
        color: var(--text-primary);
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        transition: var(--transition);
        cursor: pointer;
    }

    .dropdown-item:hover {
        color: var(--primary-color);
        background-color: rgba(0, 102, 204, 0.1);
        text-decoration: none;
    }

    /* Hero Section */
    .hero-section {
        min-height: 100vh;
        position: relative;
        padding-top: 60px;
        margin-top: 0;
    }

    .hero-slide {
        min-height: 100vh;
        position: relative;
        background-attachment: fixed;
    }

    .hero-text-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        z-index: 10;
        background: transparent;
        padding-top: 60px;
    }

    #heroCarousel .carousel-indicators {
        bottom: 30px;
        z-index: 15;
    }

    #heroCarousel .carousel-indicators button {
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        width: 12px;
        height: 12px;
        margin: 0 5px;
        border: none;
    }

    #heroCarousel .carousel-indicators button.active {
        background-color: white;
    }

    #heroCarousel .carousel-control-prev,
    #heroCarousel .carousel-control-next {
        width: 5%;
        color: white;
        z-index: 15;
    }

    #heroCarousel .carousel-control-prev-icon,
    #heroCarousel .carousel-control-next-icon {
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        width: 50px;
        height: 50px;
    }

        #heroCarousel .carousel-control-prev:hover .carousel-control-prev-icon,
        #heroCarousel .carousel-control-next:hover .carousel-control-next-icon {
            background-color: rgba(255, 255, 255, 0.6);
        }

        .hero-content h1 {
            font-family: var(--font-heading);
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: var(--space-lg);
            text-shadow: 6px 6px 20px rgba(0, 0, 0, 1), 4px 4px 12px rgba(0, 0, 0, 0.95), 2px 2px 6px rgba(0, 0, 0, 0.9), 0 0 30px rgba(0, 0, 0, 0.8);
            line-height: 1.1;
            letter-spacing: -0.025em;
            color: #ffffff;
            filter: drop-shadow(0 0 25px rgba(0, 0, 0, 0.8));
        }

        .hero-content h2 {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: var(--space-lg);
            text-shadow: 4px 4px 12px rgba(0, 0, 0, 1), 3px 3px 8px rgba(0, 0, 0, 0.95), 2px 2px 4px rgba(0, 0, 0, 0.9), 0 0 20px rgba(0, 0, 0, 0.8);
            color: #ffffff;
            filter: drop-shadow(0 0 18px rgba(0, 0, 0, 0.6));
        }

        .hero-content p {
            font-family: var(--font-primary);
            font-size: 1.1rem;
            margin-bottom: var(--space-2xl);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            font-weight: 400;
            color: #ffffff;
            text-shadow: 4px 4px 10px rgba(0, 0, 0, 1), 3px 3px 6px rgba(0, 0, 0, 0.95), 2px 2px 4px rgba(0, 0, 0, 0.9), 0 0 15px rgba(0, 0, 0, 0.8);
            filter: drop-shadow(0 0 15px rgba(0, 0, 0, 0.6));
        }

        .hero-buttons .btn {
            padding: var(--space-md) var(--space-xl);
            font-weight: 600;
            font-family: var(--font-primary);
            border-radius: var(--border-radius);
            transition: var(--transition);
            margin-right: var(--space-md);
            margin-bottom: var(--space-md);
            font-size: 1rem;
        }

    .hero-buttons .btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
    }

    /* Hero Content Container */
    .hero-content {
        background: transparent;
        border-radius: var(--border-radius);
        padding: var(--space-2xl);
        position: relative;
        z-index: 5;
    }

    /* Map Container */
    .map-container {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .map-container iframe {
        border-radius: 10px;
    }

    /* Contact Form Styling */
    .contact-form {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: var(--shadow);
    }

    .contact-form .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .contact-form .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: var(--transition);
    }

    .contact-form .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25);
    }

    .contact-form .btn {
        width: 100%;
        padding: 0.875rem 2rem;
        font-weight: 600;
        border-radius: 8px;
    }


    /* Scroll Animation */
    .scroll-animate {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-animate.animate {
        opacity: 1;
        transform: translateY(0);
    }

    /* Variasi Scroll Animation */
    .scroll-fade-left {
        opacity: 0;
        transform: translateX(-50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-fade-left.animate {
        opacity: 1;
        transform: translateX(0);
    }

    .scroll-fade-right {
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-fade-right.animate {
        opacity: 1;
        transform: translateX(0);
    }

    .scroll-scale {
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-scale.animate {
        opacity: 1;
        transform: scale(1);
    }

    .scroll-rotate {
        opacity: 0;
        transform: rotate(5deg) translateY(30px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-rotate.animate {
        opacity: 1;
        transform: rotate(0deg) translateY(0);
    }

    /* Section Styles */
    .section-title {
        font-family: var(--font-heading);
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-dark);
        text-align: center;
        margin-bottom: var(--space-2xl);
        position: relative;
        letter-spacing: -0.025em;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: var(--primary-color);
        border-radius: 2px;
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: var(--transition);
    }

    .feature-icon:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: var(--shadow-hover);
    }

    .feature-icon i {
        font-size: 2rem;
        color: white;
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        transition: var(--transition);
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .card-header {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 1.5rem;
        font-weight: 600;
    }

    .btn-primary {
        background: var(--primary-color);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: var(--border-radius);
        transition: var(--transition);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: var(--border-radius);
        transition: var(--transition);
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    /* Footer */
    .footer-section {
        background: var(--dark-color);
        color: white;
        padding: 3rem 0 1rem;
    }

    .footer-section h5 {
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .footer-section a {
        color: #ccc;
        text-decoration: none;
        transition: var(--transition);
    }

    .footer-section a:hover {
        color: var(--primary-color);
    }

    .footer-bottom {
        border-top: 1px solid #444;
        padding-top: 1rem;
        margin-top: 2rem;
        text-align: center;
        color: #999;
    }

    /* Stats Section */
    .stat-item {
        padding: 2rem 1rem;
        transition: var(--transition);
    }

    .stat-item:hover {
        transform: translateY(-5px);
    }

    .stat-item h3 {
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .stat-item p {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }

    /* Responsive Hero Section */
    @media (max-width: 1024px) {
        .hero-content h1 {
            font-size: 3rem;
        }
        
        .hero-content {
            padding: var(--space-xl);
        }
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }
        
        .hero-content h2 {
            font-size: 1.3rem;
        }
        
        .hero-content p {
            font-size: 1rem;
        }
        
        .hero-content {
            padding: var(--space-lg);
        }
        
        .hero-text-overlay {
            background: transparent;
        }
    }

    @media (max-width: 480px) {
        .hero-content h1 {
            font-size: 2rem;
        }
        
        .hero-content h2 {
            font-size: 1.1rem;
        }
        
        .hero-content p {
            font-size: 0.95rem;
        }
        
        .hero-content {
            padding: var(--space-md);
        }
        
        .hero-buttons .btn {
            padding: var(--space-sm) var(--space-lg);
            font-size: 0.9rem;
        }
    }

    /* 3D Gallery Carousel Styles */
    .gallery-3d-container {
        position: relative;
        width: 100%;
        height: 500px;
        perspective: 1500px;
        overflow: hidden;
        margin: 1rem 0 3rem 0;
    }

    .gallery-3d-carousel {
        position: relative;
        width: 100%;
        height: 100%;
        transform-style: preserve-3d;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gallery-3d-item {
        position: absolute;
        width: 500px;
        height: 350px;
        left: 50%;
        top: 50%;
        margin-left: -250px;
        margin-top: -175px;
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
    }

    .gallery-3d-card {
        width: 100%;
        height: 100%;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        position: relative;
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gallery-3d-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-3d-card:hover img {
        transform: scale(1.05);
    }

    .gallery-3d-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3), transparent);
        color: #ffffff;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gallery-3d-card:hover .gallery-3d-caption {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .gallery-3d-caption h5 {
        margin: 0 0 5px 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #ffffff;
    }

    .gallery-3d-caption p {
        margin: 0;
        font-size: 0.9rem;
        color: #ffffff;
        opacity: 0.95;
    }

    /* Position items in 3D space */
    .gallery-3d-item.active {
        z-index: 3;
        transform: translateZ(0) scale(1);
        opacity: 1;
    }

    .gallery-3d-item.prev {
        z-index: 2;
        transform: translateX(-400px) translateZ(-300px) rotateY(25deg) scale(0.8);
        opacity: 0.7;
    }

    .gallery-3d-item.next {
        z-index: 2;
        transform: translateX(400px) translateZ(-300px) rotateY(-25deg) scale(0.8);
        opacity: 0.7;
    }

    .gallery-3d-item.hidden {
        z-index: 1;
        opacity: 0;
        transform: translateZ(-500px) scale(0.5);
    }

    /* Navigation Buttons */
    .gallery-3d-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .gallery-3d-nav:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
    }

    .gallery-3d-nav.prev {
        left: 20px;
    }

    .gallery-3d-nav.next {
        right: 20px;
    }

    .gallery-3d-nav i {
        font-size: 1.2rem;
    }

    /* Indicators */
    .gallery-3d-indicators {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 10;
    }

    .gallery-3d-indicators .indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ddd;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .gallery-3d-indicators .indicator:hover {
        background: var(--primary-color);
        transform: scale(1.2);
    }

    .gallery-3d-indicators .indicator.active {
        background: var(--primary-color);
        width: 30px;
        border-radius: 10px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .gallery-3d-item {
            width: 450px;
            height: 320px;
            margin-left: -225px;
            margin-top: -160px;
        }

        .gallery-3d-item.prev {
            transform: translateX(-350px) translateZ(-250px) rotateY(25deg) scale(0.75);
        }

        .gallery-3d-item.next {
            transform: translateX(350px) translateZ(-250px) rotateY(-25deg) scale(0.75);
        }
    }

    @media (max-width: 768px) {
        .gallery-3d-container {
            height: 400px;
        }

        .gallery-3d-item {
            width: 350px;
            height: 250px;
            margin-left: -175px;
            margin-top: -125px;
        }

        .gallery-3d-item.prev,
        .gallery-3d-item.next {
            opacity: 0;
            pointer-events: none;
        }

        .gallery-3d-nav {
            width: 40px;
            height: 40px;
        }

        .gallery-3d-nav.prev {
            left: 10px;
        }

        .gallery-3d-nav.next {
            right: 10px;
        }
    }

    @media (max-width: 480px) {
        .gallery-3d-container {
            height: 350px;
        }

        .gallery-3d-item {
            width: 280px;
            height: 200px;
            margin-left: -140px;
            margin-top: -100px;
        }

        .gallery-3d-caption h5 {
            font-size: 1rem;
        }

        .gallery-3d-caption p {
            font-size: 0.8rem;
        }
    }
</style>

<div class="public-homepage">
    <!-- Header Section -->
    <header class="header-section" id="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a href="/" class="navbar-brand">
                    <div class="school-logo">
                        <img src="{{ asset('images/logo_smk_new.png') }}?v={{ time() }}" alt="SMKN 4 Logo" class="logo-image" loading="eager" width="45" height="45">
                    </div>
                    <div class="school-name">
                        <h1>SMKN 4</h1>
                        <p>Bogor</p>
                    </div>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Beranda</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a href="/public/informasi" class="nav-link" id="infoLink">Informasi <i class="fas fa-chevron-down ms-1" style="font-size: 0.7rem;"></i></a>
                            <ul class="dropdown-menu" id="infoDropdownMenu">
                                <li><a class="dropdown-item" href="/public/informasi#jurusan">Jurusan</a></li>
                                <li><a class="dropdown-item" href="/public/informasi#berita">Berita</a></li>
                                <li><a class="dropdown-item" href="/public/informasi#agenda">Agenda</a></li>
                                <li><a class="dropdown-item" href="/public/informasi#tentang">Tentang SMKN 4</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/public/galeri">Galeri</a>
                        </li>
                        @auth('web')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::guard('web')->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('user.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.register.form') }}">
                                <i class="fas fa-user-plus me-1"></i>Daftar
                            </a>
                        </li>
                        @endauth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.login.form') }}">
                                        <i class="fas fa-user me-2"></i>Login User
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/admin/login">
                                        <i class="fas fa-user-shield me-2"></i>Login Admin
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="hero-slide" style="background: url('{{ asset("images/4.JPG") }}') center/cover;">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-slide" style="background: url('{{ asset("images/foto2.JPG") }}') center/cover;">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-slide" style="background: url('{{ asset("images/foto3.jpg") }}') center/cover;">
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <!-- Fixed Text Overlay -->
        <div class="hero-text-overlay">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <div class="hero-content text-white">
                            <h1 class="display-4 fw-bold mb-4">Selamat Datang di Galerry SMKN 4 Bogor</h1>
                            <p class="lead mb-5">Sekolah Menengah Kejuruan Negeri 4 Bogor adalah institusi pendidikan unggulan yang menghasilkan lulusan berkualitas, siap kerja, dan memiliki karakter yang baik dalam menghadapi tantangan era digital.</p>
                            <div class="hero-buttons">
                                <a href="#features" class="btn btn-primary btn-lg me-3">Pelajari Lebih Lanjut</a>
                                <a href="/public/galeri" class="btn btn-outline-light btn-lg">Lihat Galeri</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section with 3D Carousel -->
    <section id="gallery" class="pt-3 pb-5 scroll-animate">
        <div class="container">
            <h2 class="section-title"><a href="/public/galeri" style="text-decoration: none; color: inherit; cursor: pointer;">Galeri Sekolah</a></h2>
            
            <!-- 3D Carousel Container -->
            <div class="gallery-3d-container">
                <div class="gallery-3d-carousel" id="gallery3DCarousel">
                    <div class="gallery-3d-item active" data-index="0">
                        <div class="gallery-3d-card">
                            <img src="{{ asset('images/galeri1.JPG') }}?v={{ time() }}" alt="Galeri 1" loading="lazy">
                            <div class="gallery-3d-caption">
                                <h5>Kegiatan Siswa</h5>
                                <p>Berbagai kegiatan pembelajaran dan ekstrakurikuler</p>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-3d-item" data-index="1">
                        <div class="gallery-3d-card">
                            <img src="{{ asset('images/galeri2.JPG') }}?v={{ time() }}" alt="Galeri 2" loading="lazy">
                            <div class="gallery-3d-caption">
                                <h5>Fasilitas Sekolah</h5>
                                <p>Fasilitas modern dan lengkap</p>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-3d-item" data-index="2">
                        <div class="gallery-3d-card">
                            <img src="{{ asset('images/galeri3.JPG') }}?v={{ time() }}" alt="Galeri 3" loading="lazy">
                            <div class="gallery-3d-caption">
                                <h5>Prestasi Siswa</h5>
                                <p>Berbagai prestasi dan pencapaian siswa</p>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-3d-item" data-index="3">
                        <div class="gallery-3d-card">
                            <img src="{{ asset('images/1757399234.jpg') }}?v={{ time() }}" alt="Galeri 4" loading="lazy">
                            <div class="gallery-3d-caption">
                                <h5>Kegiatan Ekstrakurikuler</h5>
                                <p>Mengembangkan bakat siswa</p>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-3d-item" data-index="4">
                        <div class="gallery-3d-card">
                            <img src="{{ asset('images/1757399253.jpg') }}?v={{ time() }}" alt="Galeri 5" loading="lazy">
                            <div class="gallery-3d-caption">
                                <h5>Praktik Kejuruan</h5>
                                <p>Praktik langsung sesuai jurusan</p>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-3d-item" data-index="5">
                        <div class="gallery-3d-card">
                            <img src="{{ asset('images/1757399275.jpg') }}?v={{ time() }}" alt="Galeri 6" loading="lazy">
                            <div class="gallery-3d-caption">
                                <h5>Lingkungan Sekolah</h5>
                                <p>Suasana nyaman dan kondusif</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <button class="gallery-3d-nav prev" onclick="moveGallery3D('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="gallery-3d-nav next" onclick="moveGallery3D('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Indicators -->
                <div class="gallery-3d-indicators">
                    <span class="indicator active" onclick="goToSlide3D(0)"></span>
                    <span class="indicator" onclick="goToSlide3D(1)"></span>
                    <span class="indicator" onclick="goToSlide3D(2)"></span>
                    <span class="indicator" onclick="goToSlide3D(3)"></span>
                    <span class="indicator" onclick="goToSlide3D(4)"></span>
                    <span class="indicator" onclick="goToSlide3D(5)"></span>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="/public/galeri" class="btn btn-primary btn-lg">
                    <i class="fas fa-images me-2"></i>Lihat Semua Galeri
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 scroll-fade-right">
        <div class="container">
            <h2 class="section-title">Keunggulan SMKN 4 Bogor</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h5 class="card-title">Pendidikan Berkualitas</h5>
                            <p class="card-text">Kurikulum terdepan dengan pengajar berpengalaman dan fasilitas modern</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h5 class="card-title">Teknologi Modern</h5>
                            <p class="card-text">Fasilitas pembelajaran dengan teknologi terkini dan laboratorium canggih</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h5 class="card-title">Prestasi Terbaik</h5>
                            <p class="card-text">Mencetak lulusan berprestasi di berbagai bidang dan kompetisi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- Contact Section -->
    <section id="contact" class="py-5 scroll-animate">
        <div class="container">
            <h2 class="section-title">Lokasi Sekolah</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="contact-info text-center">
                        <h4 class="mb-4">SMK Negeri 4 Bogor (Nebrazka)</h4>
                        <p class="mb-4">Jl. Raya Tajur, Kp. Buntar, RT.02/RW.08, Kel. Muara sari, Kec. Bogor Selatan, RT.03/RW.08, Muarasari, Kec. Bogor Sel., Kota Bogor, Jawa Barat 16137</p>
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.04983961244!2d106.82211897499403!3d-6.640733393353795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c8b16ee07ef5%3A0x14ab253dd267de49!2sSMK%20Negeri%204%20Bogor%20(Nebrazka)!5e0!3m2!1sid!2sid!4v1757382662477!5m2!1sid!2sid" width="100%" height="400" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>SMKN 4 Bogor</h5>
                    <p>Membangun generasi unggul melalui pendidikan berkualitas dan inovasi teknologi.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/public/informasi#jurusan">Jurusan</a></li>
                        <li><a href="/public/informasi#berita">Berita</a></li>
                        <li><a href="/public/informasi#agenda">Agenda</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak & Informasi</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Jl. Raya Tajur, Kp. Buntar, Kel. Muara sari, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16137</p>
                    <p><i class="fas fa-phone me-2"></i> (0251) 1234-5678</p>
                    <p><i class="fas fa-envelope me-2"></i> info@smkn4.sch.id</p>
                    <p><i class="fas fa-clock me-2"></i> Senin - Jumat: 07:00 - 16:00</p>
                    <p><i class="fas fa-clock me-2"></i> Sabtu: 07:00 - 12:00</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 SMKN 4 Bogor. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>

<script>
// Scroll Animation
function handleScrollAnimation() {
    const elements = document.querySelectorAll('.scroll-animate, .scroll-fade-left, .scroll-fade-right, .scroll-scale, .scroll-rotate');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('animate');
        }
    });
}

// Header scroll effect
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header-section');
    if (window.scrollY > 100) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
    
    handleScrollAnimation();
});

// Dropdown functionality - show on hover, allow navigation on click
document.addEventListener('DOMContentLoaded', function() {
    const infoLink = document.getElementById('infoLink');
    const dropdownMenu = document.getElementById('infoDropdownMenu');
    const navItem = infoLink?.closest('.nav-item');
    
    if (navItem && dropdownMenu) {
        // Show dropdown on hover for desktop
        navItem.addEventListener('mouseenter', function() {
            dropdownMenu.classList.add('show');
        });
        
        navItem.addEventListener('mouseleave', function() {
            dropdownMenu.classList.remove('show');
        });
        
        // Allow normal navigation when clicking the link
        // Dropdown items will navigate to their respective sections
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-item.dropdown')) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    handleScrollAnimation();
    
    // Initialize 3D Gallery Carousel
    init3DGallery();
});

// 3D Gallery Carousel JavaScript
let currentSlide3D = 0;
let totalSlides3D = 6;
let autoPlayInterval;

function init3DGallery() {
    updateGallery3D();
    startAutoPlay();
    
    // Pause on hover
    const container = document.querySelector('.gallery-3d-container');
    if (container) {
        container.addEventListener('mouseenter', stopAutoPlay);
        container.addEventListener('mouseleave', startAutoPlay);
    }
}

function updateGallery3D() {
    const items = document.querySelectorAll('.gallery-3d-item');
    const indicators = document.querySelectorAll('.gallery-3d-indicators .indicator');
    
    items.forEach((item, index) => {
        item.classList.remove('active', 'prev', 'next', 'hidden');
        
        if (index === currentSlide3D) {
            item.classList.add('active');
        } else if (index === (currentSlide3D - 1 + totalSlides3D) % totalSlides3D) {
            item.classList.add('prev');
        } else if (index === (currentSlide3D + 1) % totalSlides3D) {
            item.classList.add('next');
        } else {
            item.classList.add('hidden');
        }
    });
    
    indicators.forEach((indicator, index) => {
        if (index === currentSlide3D) {
            indicator.classList.add('active');
        } else {
            indicator.classList.remove('active');
        }
    });
}

function moveGallery3D(direction) {
    if (direction === 'next') {
        currentSlide3D = (currentSlide3D + 1) % totalSlides3D;
    } else {
        currentSlide3D = (currentSlide3D - 1 + totalSlides3D) % totalSlides3D;
    }
    updateGallery3D();
}

function goToSlide3D(index) {
    currentSlide3D = index;
    updateGallery3D();
}

function startAutoPlay() {
    stopAutoPlay();
    autoPlayInterval = setInterval(() => {
        moveGallery3D('next');
    }, 4000);
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') {
        moveGallery3D('prev');
    } else if (e.key === 'ArrowRight') {
        moveGallery3D('next');
    }
});

// Touch swipe support
let touchStartX = 0;
let touchEndX = 0;

document.querySelector('.gallery-3d-container')?.addEventListener('touchstart', e => {
    touchStartX = e.changedTouches[0].screenX;
});

document.querySelector('.gallery-3d-container')?.addEventListener('touchend', e => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    if (touchEndX < touchStartX - 50) {
        moveGallery3D('next');
    }
    if (touchEndX > touchStartX + 50) {
        moveGallery3D('prev');
    }
}
</script>

@endsection