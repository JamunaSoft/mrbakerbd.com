<style>
    /* Star Rating Styles */
    .star-rating {
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 10px;
        margin: 20px 0;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        font-size: 3rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input[type="radio"]:checked ~ label {
        color: #ffc107;
    }

    .star-rating:hover label {
        color: #ddd;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffc107;
    }

    .rating-section {
        padding: 30px;
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        text-align: center;
    }

    .rating-text {
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .star-rating label {
            font-size: 2.5rem;
        }

        .rating-section {
            padding: 20px;
        }
    }
    @media (max-width: 768px) {
        .star-rating label {
            font-size: 2.5rem;
        }

        .rating-section {
            padding: 20px;
        }

        #feedback-reason {
            text-align: left !important;
            margin-bottom: 12px !important;
        }
    }
</style>
