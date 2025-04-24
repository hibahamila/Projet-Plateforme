
<div class="row">
    <div class="col-sm-12">
        <div class="settings-card">
            <div class="settings-header">
                <div class="settings-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="settings-title">
                    <h4>Paramètres du compte</h4>
                    <p class="settings-subtitle">Gérez vos informations personnelles et vos paramètres de sécurité.</p>
                </div>
            </div>

            <div class="settings-divider"></div>

            <div class="settings-list">
                <a href="#" class="list-group-item list-group-item-action account-link" data-link="email">
                    <div class="settings-item-content">
                        <div class="settings-item-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="settings-item-details">
                            <h5>Adresse Email</h5>
                            <p>Modifier votre adresse email</p>
                        </div>
                    </div>
                    <div class="settings-item-action chevron-container">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

                <div class="settings-item-divider"></div>

                <a href="#" class="list-group-item list-group-item-action account-link" data-link="password">
                    <div class="settings-item-content">
                        <div class="settings-item-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="settings-item-details">
                            <h5>Mot de Passe</h5>
                            <p>Modifier votre mot de passe</p>
                        </div>
                    </div>
                    <div class="settings-item-action chevron-container">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .settings-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 24px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .settings-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .settings-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background-color: #4361ee;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
    }

    .settings-icon i {
        color: white;
        font-size: 18px;
    }

    .settings-title h4 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 6px;
        font-size: 20px;
    }

    .settings-subtitle {
        color: #718096;
        font-size: 14px;
        margin-bottom: 0;
    }

    .settings-divider {
        height: 1px;
        background-color: #e2e8f0;
        margin: 16px 0 24px 0;
    }

    .settings-list {
        padding: 0;
    }

    .account-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        border-radius: 8px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        border: 1px solid #f0f4f8;
        margin-bottom: 10px;
        background-color: #f9fafc;
    }

    .account-link:hover {
        background-color: #f5f7fa;
        transform: translateY(-2px);
    }

    .settings-item-content {
        display: flex;
        align-items: center;
    }

    .settings-item-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #e9effd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
    }

    .settings-item-icon i {
        color: #4361ee;
        font-size: 16px;
    }

    .settings-item-details h5 {
        color: #2d3748;
        font-weight: 500;
        margin-bottom: 4px;
        font-size: 16px;
    }

    .settings-item-details p {
        color: #718096;
        font-size: 14px;
        margin-bottom: 0;
    }

    .chevron-container {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .settings-item-action i {
        color: #cbd5e0;
        font-size: 14px;
    }

    .account-link:hover .settings-item-action i {
        color: #a0aec0;
    }

    .settings-item-divider {
        height: 1px;
        background-color: #f0f4f8;
        margin: 0;
    }

    @media (max-width: 768px) {
        .settings-card {
            padding: 20px 16px;
        }

        .account-link {
            padding: 12px;
        }
    }
</style>
