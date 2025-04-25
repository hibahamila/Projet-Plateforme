{{-- 
<div class="modal fade" id="formation-modal-{{ $formation->id }}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $formation->title }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="product-box row">
                    <div class="product-img col-lg-6">
                        <img class="img-fluid" src="{{ asset('storage/' . $formation->image) }}" alt="{{ $formation->title }}" />
                    </div>
                    <div class="product-details col-lg-6 text-start">
                        <a href="{{ route('formationshow', $formation->id) }}"> 
                            <h4>{{ $formation->title }}</h4>
                        </a>
                        <div class="product-price">
                            @if($formation->type == 'payante')
                                @if($formation->discount > 0)
                                {{ number_format($formation->final_price, 2) }} Dt
                                <del>{{ number_format($formation->price, 2) }} Dt</del>
                                @else
                                {{ number_format($formation->price, 2) }} Dt
                                @endif
                            @else
                                &nbsp;
                            @endif
                        </div>
                        <div class="product-view">
                            <p class="mb-0">{{ $formation->description }}</p>
                            <div class="mt-3">
                                <p><strong>Places:</strong> {{ $formation->total_seats }}</p>
                                <p><strong>Durée:</strong> {{ $formation->duration }}</p>
                                <p><strong>Date début:</strong> {{ \Carbon\Carbon::parse($formation->start_date)->format('d/m/Y') }}</p>
                                <p><strong>Date fin:</strong> {{ \Carbon\Carbon::parse($formation->end_date)->format('d/m/Y') }}</p>
                                <p><strong>Nombre de cours:</strong> {{ $formation->courses->count() }}</p>
                            </div>
                        </div>
                        <div class="addcart-btn">
                            <a class="btn btn-primary" href="/panier">Ajouter au panier</a>
                            <a class="btn btn-primary" href="{{ route('formationshow', $formation->id) }}">Voir détails</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{asset('assets/js/MonJs/formations-modal.js')}}"></script>
<script src="{{asset('assets/js/MonJs/cart.js')}}"></script>
 --}}
 <div class="modal fade" id="formation-modal-{{ $formation->id }}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $formation->title }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="product-box row">
                    <div class="product-img col-lg-6">
                        <img class="img-fluid" src="{{ asset('storage/' . $formation->image) }}" alt="{{ $formation->title }}" />
                    </div>
                    <div class="product-details col-lg-6 text-start">
                        <a href="{{ route('formationshow', $formation->id) }}">
                            <h4>{{ $formation->title }}</h4>
                        </a>
                        <div class="product-price">
                            @if($formation->type == 'payante')
                                @if($formation->discount > 0)
                                {{ number_format($formation->final_price, 2) }} Dt
                                <del>{{ number_format($formation->price, 2) }} Dt</del>
                                @else
                                {{ number_format($formation->price, 2) }} Dt
                                @endif
                            @else
                                &nbsp;
                            @endif
                        </div>
                        <div class="product-view">
                            <div class="description-container">
                                <div class="truncated-description">
                                    @php
                                        $truncated = Str::limit($formation->description, 150);
                                    @endphp
                                    {{ $truncated }}
                                </div>
                                <div class="full-description" style="display: none;">
                                    {{ $formation->description }}
                                </div>
                                @if(strlen($formation->description) > 150)
                                    <button class="toggle-description">Voir plus</button>
                                @endif
                            </div>
                            <div class="mt-3">
                                <p><strong>Places:</strong> {{ $formation->total_seats }}</p>
                                <p><strong>Durée:</strong> {{ $formation->duration }}</p>
                                <p><strong>Date début:</strong> {{ \Carbon\Carbon::parse($formation->start_date)->format('d/m/Y') }}</p>
                                <p><strong>Date fin:</strong> {{ \Carbon\Carbon::parse($formation->end_date)->format('d/m/Y') }}</p>
                                <p><strong>Nombre de cours:</strong> {{ $formation->courses->count() }}</p>
                            </div>
                        </div>
                        <div class="addcart-btn">
                            <a class="btn btn-primary" href="/panier">Ajouter au panier</a>
                            <a class="btn btn-primary" href="{{ route('formationshow', $formation->id) }}">Voir détails</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>