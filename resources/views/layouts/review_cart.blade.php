@auth
@if(auth()->user()->isMaterialReviewerRole() || auth()->user()->isWorkItemReviewer() || auth()->user()->isToolsEquipmentReviewerRole() || auth()->user()->isManPowerReviewer())
    <button type="button" class="btn btn-primary js-open-review-cart review-cart-btn-empty"
            title="Review Cart"
            style="position: fixed; bottom: 24px; right: 24px; z-index: 1050; border-radius: 50%; width: 56px; height: 56px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
        <i class="fa fa-tasks"></i>
        <span class="badge bg-danger js-review-cart-count"
              style="position: absolute; top: -4px; right: -4px;">0</span>
    </button>

    <style>
        .review-cart-btn-empty .js-review-cart-count { display: none; }
    </style>

    <div class="modal fade" id="reviewCartModal" tabindex="-1" role="dialog" aria-labelledby="reviewCartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewCartModalLabel">Review Cart</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body js-review-cart-body" style="max-height: 60vh; overflow-y: auto;">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endauth
