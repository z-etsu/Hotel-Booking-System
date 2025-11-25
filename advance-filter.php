<?php
// This file is intended to be fetched and injected as a modal into rooms.php
?>

<!-- Advanced Filter Modal (fragment) -->
<div id="advancedFilterModal" class="success-modal">
    <div class="modal-content">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h2 style="margin:0;">Advanced Room Filters</h2>
            <button id="modalCloseBtn" style="background:none;border:none;font-size:1.6rem;cursor:pointer;">&times;</button>
        </div>

        <div class="adv-filter-body">
            <div style="margin-bottom:1rem;">
                <h3 style="margin:0 0 0.5rem 0;">💵 Price Range (Per Night)</h3>
                <div style="display:flex;gap:0.75rem;">
                    <div style="flex:1;">
                        <label for="advMinPrice">Minimum Price ($)</label>
                        <input id="advMinPrice" type="number" min="0" placeholder="0" style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:6px;">
                    </div>
                    <div style="flex:1;">
                        <label for="advMaxPrice">Maximum Price ($)</label>
                        <input id="advMaxPrice" type="number" min="0" placeholder="999" style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:6px;">
                    </div>
                </div>
            </div>

            <div style="margin-bottom:1rem;">
                <h3 style="margin:0 0 0.5rem 0;">👨‍👩‍👧‍👦 Minimum Number of Guests</h3>
                <input id="advGuests" type="number" min="1" max="10" placeholder="1" style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:6px;">
            </div>

            <div style="margin-bottom:1rem;">
                <h3 style="margin:0 0 0.5rem 0;">✨ Amenities & Features</h3>
                <div id="advancedAmenitiesGrid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:0.5rem;max-height:220px;overflow:auto;padding:0.25rem;border:1px solid #f0f0f0;border-radius:6px;background:#fff;">
                    <!-- Populated dynamically -->
                </div>
            </div>

            <div id="pageResultsContainer" style="margin-top:1rem;">
                <h3 style="margin:0 0 0.5rem 0;">Results</h3>
                <div id="pageResultsGrid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:0.75rem;max-height:300px;overflow:auto;padding:0.25rem;">
                    <!-- Filtered room results will appear here (rendered by rooms.js) -->
                </div>
            </div>
        </div>

        <div style="display:flex;gap:0.5rem;justify-content:flex-end;margin-top:1rem;">
            <button id="advancedResetBtn" class="btn" style="background:#eee;color:#333;border:1px solid #ddd;padding:0.6rem 0.9rem;border-radius:6px;">Reset Filters</button>
            <button id="advancedCancelBtn" class="btn" style="background:#ccc;color:#111;border:1px solid #bbb;padding:0.6rem 0.9rem;border-radius:6px;">Cancel</button>
            <button id="advancedApplyBtn" class="btn" style="background:var(--accent);color:#fff;padding:0.6rem 0.9rem;border-radius:6px;">Apply Filters</button>
        </div>
    </div>
</div>

<style>
    /* Minimal modal styles to match register-success modal look */
    .success-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .success-modal.show {
        display: flex;
    }

    .modal-content {
        background-color: #ffffff;
        padding: 1.25rem;
        border-radius: 10px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
        max-width: 960px;
        width: 95%;
        max-height: 85vh;
        overflow: auto;
    }

    /* Ensure modal text is visible against the overlay */
    .modal-content,
    .modal-content h2,
    .modal-content h3,
    .modal-content label,
    .modal-content p,
    .modal-content input {
        color: #222 !important;
    }

    .modal-content input::placeholder {
        color: #999 !important;
    }

    @media (max-width:600px) {
        .modal-content {
            padding: 1rem;
            width: 95%;
        }
    }
</style>