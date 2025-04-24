<?php

if (!function_exists('getStarRating')) {
    function getStarRating($rating) {
        $fullStars = floor($rating); // Nombre d'étoiles pleines ⭐
        $halfStar = ($rating - $fullStars) >= 0.5 ? true : false; // Demi-étoile ⭐½
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0); // Étoiles vides ☆

        $starsHtml = '';

        for ($i = 0; $i < $fullStars; $i++) {
            $starsHtml .= '<i class="fas fa-star text-warning"></i>'; // ⭐ Pleine
        }

        if ($halfStar) {
            $starsHtml .= '<i class="fas fa-star-half-alt text-warning"></i>'; // ⭐½ Demi
        }

        for ($i = 0; $i < $emptyStars; $i++) {
            $starsHtml .= '<i class="far fa-star text-warning"></i>'; // ☆ Vide
        }

        return $starsHtml;
    }
}
