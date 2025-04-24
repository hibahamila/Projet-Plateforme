
<div class="row">
    <div class="col-sm-12">
        <form class="ajax-form" action="{{ route('profile.update') }}" method="POST" data-reload-tab="profile">
            @csrf
            @method('PUT')
            <div class="form-group mb-4">
                <div class="input-wrapper">
                    <label class="form-label">Nom</label>
                    <div class="input-container">
                        <i class="fas fa-user input-icon"></i>
                        <input class="form-control custom-input" type="text" name="name" value="{{ $user->name }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group mb-4">
                <div class="input-wrapper">
                    <label class="form-label">Prénom</label>
                    <div class="input-container">
                        <i class="fas fa-signature input-icon"></i>
                        <input class="form-control custom-input" type="text" name="lastname" value="{{ $user->lastname }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group mb-4">
                <div class="input-wrapper">
                    <label class="form-label">Téléphone</label>
                    <div class="phone-input-container">
                        <i class="fas fa-phone-alt input-icon"></i>
                        <div class="country-code">+216</div>
                        <input class="form-control phone-input" type="text" name="phone" value="{{ preg_replace('/^\+216\s*/', '', $user->phone) }}" placeholder="90120430" required>
                    </div>
                </div>
                <div class="hint-text">
                    <i class="fas fa-info-circle hint-icon"></i> Format: 8 chiffres
                </div>
            </div>
            <div class="form-group mt-5 text-center">
                <button class="btn btn-save" type="submit">
                    <i class="fas fa-save me-2"></i>Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>
<style>
 .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .input-wrapper {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-weight: 500;
        color: #4a4a4a;
        margin-bottom: 8px;
    }

    .input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        color: #4361ee;
        font-size: 1rem;
    }

    .custom-input {
        border-radius: 8px;
        padding: 12px 15px 12px 45px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
        width: 100%;
        font-size: 16px;
        color: #333;
        background-color: #fff;
    }

    .custom-input:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .phone-input-container {
        position: relative;
        display: flex;
        align-items: center;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background-color: #fff;
    }

    .phone-input-container:focus-within {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .country-code {
        padding: 12px 10px 12px 45px;
        font-size: 16px;
        color: #333;
        border-right: 1px solid #e0e0e0;
        background-color: #f9f9f9;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .phone-input {
        border: none;
        padding: 12px 15px;
        width: 100%;
        font-size: 16px;
        color: #333;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .phone-input:focus {
        outline: none;
        box-shadow: none;
    }

    .hint-text {
        font-size: 12px;
        color: #6c757d;
        margin-top: 6px;
        margin-left: 5px;
    }

    .hint-icon {
        font-size: 12px;
        margin-right: 5px;
    }

    .btn-save {
        background-color: #4361ee;
        color: white !important;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 16px;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .btn-save:hover {
        background-color: #3951d0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    @media (max-width: 768px) {
        .phone-input-container {
            width: 100%;
        }
    }
</style>
