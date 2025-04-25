
    @extends('layouts.admin.master')

    @section('content')

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="formations-url" content="{{ route('formations') }}">
        <title>Votre Panier</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/panier.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/reservation.css') }}">


        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    </head>

    <body>
        <div class="container" style="background-color: white !important;">
            <div class="panier-header">
                <h1>Panier d'achat</h1>
                <div class="panier-count">{{ count($panierItems) }} formation(s)</div>
            </div>
            <div id="app" data-formations-url="{{ route('formations') }}">

            @if(count($panierItems) > 0)
            <div class="panier-content">
                <div class="formations-list">
                    @foreach($panierItems as $item)
                    <div class="formation-item" data-formation-id="{{ $item->Training->id }}">
                        <div class="formation-image">
                            @if($item->Training->image)
                                <img src="{{ asset('storage/' . $item->Training->image) }}" alt="{{ $item->Training->title }}">
                            @else
                                <div class="placeholder-image">Image non disponible</div>
                            @endif
                        </div>
                        
                        <div class="formation-details">
                            <h3 class="formation-title">{{ $item->Training->title }}</h3>
                            <div class="formation-instructor">
                                @if($item->Training->user)
                                    {{ $item->Training->user->name }} {{ $item->Training->user->lastname ?? '' }}
                                    @if($item->Training->user->role)
                                    | {{ $item->Training->user->role }}
                                    @endif
                                @else
                                    Instructeur non défini
                                @endif
                            </div>
                            
                        
                            
                            @if(isset($item->Training->average_rating) && $item->Training->average_rating > 0)
                            <div class="formation-rating">
                                <div class="rating-stars">
                                    @php
                                        $rating = $item->Training->average_rating;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = ($rating - $fullStars) >= 0.25;
                                    @endphp
                                    
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            <i class="fas fa-star"></i>
                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="rating-value">{{ number_format($rating, 1) }}</span>
                                </div>
                                <span class="rating-count">({{ $item->Training->total_feedbacks ?? 0 }})</span>
                            </div>
                            @endif
                            
                            <div class="formation-meta">
                                @if($item->Training->duration && $item->Training->duration != '00:00')
                                <span>{{ $item->Training->formatted_duration }}</span>

                                    {{-- <span>{{ formatDuration($item->Training->duration) }}</span> --}}
                                    @if(isset($item->Training->courses) && count($item->Training->courses) > 0)
                                        <span class="dot-separator">•</span>
                                        <span>{{ count($item->Training->courses) }} cours</span>
                                    @endif
                                @elseif(isset($item->Training->courses) && count($item->Training->courses) > 0)
                                    <span> {{ count($item->Training->courses) }} cours</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="formation-actions">
                        
                            <div class="action-links">
                                <a href="#" class="remove-link" data-formation-id="{{ $item->Training->id }}">Supprimer</a>
                            </div>
                        

                        
                            <div class="formation-price">
                                @if($item->Training->type == 'gratuite' || $item->Training->price == 0)
                                    <div class="final-price">Gratuite</div>
                                @elseif($item->Training->discount > 0)
                                    <div class="price-with-discount">
                                        <span class="original-price">{{ number_format($item->Training->price, 3) }} DT</span>
                                        <span class="discount-badge">
                                            -{{ $item->Training->discount }}%
                                        </span>
                                    </div>
                                    <div class="final-price">{{ number_format($item->Training->final_price, 3) }} DT</div>
                                @else
                                    <div class="final-price">{{ number_format($item->Training->price, 3) }} DT</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
            
                <div class="panier-summary usd-style">
                    <div class="summary-title">Total:</div>
                    
                    <div class="total-price">{{ number_format($totalPrice, 2) }} Dt</div>
                    
                    @if(isset($hasDiscount) && $hasDiscount)
                        <div class="original-price">{{ number_format($totalWithoutDiscount, 2) }} Dt</div>
                        <div class="discount-percentage"> -{{ $discountPercentage }}%</div>
                    @endif
                    

                
                    </div>
                </div>
            </div>
            @else
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Votre panier est vide</p>
                <a href="formation/formations">Découvrir des formations</a>
            </div>
            @endif
        </div>
        </div>
        
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </body>
    </html>
    @endsection
    <script src="{{ asset('assets/js/MonJs/toast/toast.js') }}"></script>

    <script src="{{ asset('assets/js/MonJs/formations/panier.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/formations/reservation.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/formations/formations-modal.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/cart.js') }}"></script>






{{-- @php
function formatDuration($duration) {
    if (!$duration || $duration === '00:00' || $duration === '0:0' || $duration === '0:00' || $duration === '00:0') {
        return '0 heures';
    }
    
    $parts = explode(':', $duration);
    if (count($parts) !== 2) return '0 heures';
    
    $hours = (int)$parts[0];
    $minutes = (int)$parts[1];
    
    if ($hours === 0 && $minutes === 0) {
        return '0 heures';
    } else if ($hours === 0) {
        return $minutes . ' min';
    } else if ($minutes === 0) {
        return $hours . ' h';
    } else {
        return $hours . ',' . $minutes . ' heures totales';
    }
}
@endphp --}}


@php
function formatDuration($duration) {
    if (empty($duration) || $duration === '00:00:00' || $duration === '0:0:0') {
        return '0 heures';
    }
    
    $parts = explode(':', $duration);
    
    // Gère à la fois HH:MM:SS et HH:MM
    $hours = (int)($parts[0] ?? 0);
    $minutes = (int)($parts[1] ?? 0);
    $seconds = (int)($parts[2] ?? 0);

    $result = [];
    if ($hours > 0) $result[] = $hours . ' h';
    if ($minutes > 0) $result[] = $minutes . ' min';
    if ($seconds > 0) $result[] = $seconds . ' s';

    return !empty($result) ? implode(' ', $result) : '0 heures';
}
@endphp

</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Récupérer le compteur depuis les données de la vue
        const cartCount = {{ $cartCount ?? 0 }};
        
        // Mettre à jour immédiatement le localStorage et le badge
        localStorage.setItem('cartCount', cartCount.toString());
        
        // S'assurer que la fonction updateCartBadge existe
        if (typeof updateCartBadge === 'function') {
            updateCartBadge(cartCount);
        } else {
            // Fallback si la fonction n'est pas définie
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = cartCount;
                cartBadge.style.display = cartCount > 0 ? 'block' : 'none';
            }
        }
    });
    </script>