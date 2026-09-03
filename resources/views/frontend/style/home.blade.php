<style>
    .masonry-grid {
        column-count: 3;
        column-gap: 1.5rem;
    }

    @media (max-width: 992px) {
        .masonry-grid {
            column-count: 2;
        }
    }

    @media (max-width: 576px) {
        .masonry-grid {
            column-count: 1;
        }
    }

    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s ease;
    }

    .masonry-item:hover {
        transform: translateY(-4px);
    }

    .masonry-img-wrapper {
        position: relative;
        overflow: hidden;
    }

    .masonry-img-wrapper img {
        width: 100%;
        display: block;
        border-radius: 8px;
    }

    /* Overlay caption inside image */
    .masonry-caption-overlay {
        position: absolute;
        bottom: 15px;
        right: 15px;
        display: flex;
        justify-content: flex-end;
        align-items: flex-end;
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
    }

    .caption-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .masonry-caption-overlay .btn {
        background-image: url('images/gold-bg.jpg');
        background-size: cover;
        background-position: center;
        color: #2f3194;
        border: none;
    }
    .masonry-caption-overlay .btn:hover {
        background-color: #e6c200; /* Slightly darker gold on hover */
        color: white;
    }

    .box-info-modern-title{
        font-size:18px;
    }

    /* Desktop styles (default) */
    .text-color-white {
        color: #fff !important;
    }

    /* Mobile styles for text color */
    @media (max-width: 991px) {
        .text-color-white {
            color: #000 !important;
        }
    }

    /* Mobile-responsive styles for the section */
    @media (max-width: 991px) {
        .section-sm {
            padding-top: 10px !important;
            padding-bottom: 20px !important;
        }

        .section-sm h2 {
            font-size: 28px !important; /* Smaller heading for mobile */
            margin-bottom: 15px !important;
        }

        .box-info-modern {
            text-align: center; /* Center content on mobile */
        }

        .box-info-modern-figure {
            width: 100% !important; /* Full-width images */
            height: auto !important; /* Maintain aspect ratio */
            max-width: 300px; /* Prevent oversized images */
            margin: 0 auto; /* Center images */
        }

        .box-info-modern-title {
            font-size: 18px !important; /* Smaller title font */
            margin-top: 10px !important;
        }

        .row > div {
            margin-bottom: 15px !important; /* Adjust spacing between cards */
        }
    }

    /* Ensure images are responsive on all screens */
    .box-info-modern-figure {
        width: 100%;
        height: auto;
        object-fit: cover; /* Maintain aspect ratio and cover container */
    }

    /* Optional: Hover effect for desktop */
    @media (min-width: 992px) {
        .box-info-modern:hover .box-info-modern-figure {
            opacity: 0.9; /* Subtle hover effect */
            transition: opacity 0.3s ease;
        }
    }
</style>
<style>
    .view-all-circle {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #000;
        transition: all 0.3s ease;
        background-color: transparent;
    }

    .view-all-circle:hover {
        background-color: #2f3194;
        border-color: #2f3194;
    }

    .view-all-circle svg {
        stroke: #000;
        transition: all 0.3s ease;
    }

    .view-all-circle:hover svg {
        stroke: #fff;
    }

    @media (max-width: 576px) {
        .view-all-circle {
            position: relative;
            top: auto;
            transform: none;
            margin: 20px auto 0;
            display: flex;
        }
        .position-relative {
            padding-right: 0 !important;
        }
        .img-responsive {
            width: 100%!important;
            height: auto!important;
        }
        .product-figure{
            min-height: 130px!important;
        }
        .product{
            min-height: 130px!important;
            height: 100%!important;
        }
        .product-title{
            font-size: 11px!important;
        }
        .product-price{
            font-size: 11px!important;
        }
        .product-button .button {
            width: 35px;
            height: 35px;
            font-size: 10px;
            line-height: 32px;
        }
    }
</style>

<style>
    .carousel-slide-section {
        background-size: cover;
        background-position: center;
        position: relative;
        min-height: 130px; /* Mobile default */
    }

    @media (min-width: 768px) {
        .carousel-slide-section {
            min-height: 300px;
        }
    }

    @media (min-width: 992px) {
        .carousel-slide-section {
            min-height: 500px;
        }
    }

    .carousel-caption-box {
        padding: 1rem;
        background-color: rgba(0, 0, 0, 0.4); /* Optional dark overlay */
        border-radius: 0.5rem;
    }
</style>
