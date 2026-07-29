(function () {
    var CART_KEY = 'reviewCart';

    var ENTITY_CONFIG = {
        material:       { label: 'Material',          updateListUrl: '/material/update-list/' },
        workItem:       { label: 'Work Item',          updateListUrl: '/workItem/update-list/' },
        equipmentTools: { label: 'Tools & Equipment',  updateListUrl: '/equipmentTools/update-list/' },
        manPower:       { label: 'Man Power',          updateListUrl: '/manPower/update-list/' },
    };

    function emptyCart() {
        return { material: [], workItem: [], equipmentTools: [], manPower: [] };
    }

    function getCart() {
        try {
            var raw = window.localStorage.getItem(CART_KEY);
            if (!raw) return emptyCart();
            var parsed = JSON.parse(raw);
            return $.extend(emptyCart(), parsed);
        } catch (e) {
            return emptyCart();
        }
    }

    function saveCart(cart) {
        window.localStorage.setItem(CART_KEY, JSON.stringify(cart));
    }

    function cartCount(cart) {
        var total = 0;
        $.each(ENTITY_CONFIG, function (key) {
            total += (cart[key] || []).length;
        });
        return total;
    }

    function renderBadge() {
        var count = cartCount(getCart());
        $('.js-review-cart-count').text(count);
        $('.js-open-review-cart').toggleClass('review-cart-btn-empty', count === 0);
    }

    function syncButtonStates() {
        var cart = getCart();
        $('.js-add-to-review-cart').each(function () {
            var _this = $(this);
            var entity = _this.data('entity');
            var id = _this.data('id');
            var inCart = (cart[entity] || []).some(function (item) { return String(item.id) === String(id); });
            var $icon = _this.is('i') ? _this : _this.find('i').first();
            var title = inCart ? 'Remove from Review List' : 'Add to Review List';

            _this.toggleClass('js-added-to-cart', inCart);
            $icon.toggleClass('fa-flag', !inCart);
            $icon.toggleClass('fa-flag-checkered', inCart);
            _this.attr('title', title);
            _this.find('.js-review-list-label').text(title);
        });
    }

    function renderCartBody() {
        var cart = getCart();
        var $body = $('.js-review-cart-body');
        $body.empty();

        var hasAny = cartCount(cart) > 0;
        if (!hasAny) {
            $body.append('<p class="text-center text-muted mb-0">Your review cart is empty</p>');
            return;
        }

        $.each(ENTITY_CONFIG, function (entity, cfg) {
            var items = cart[entity] || [];
            if (items.length === 0) return;

            var $section = $('<div class="mb-4"></div>');
            $section.append('<h6 class="mb-2">' + cfg.label + ' (' + items.length + ')</h6>');

            var $list = $('<ul class="list-group mb-2"></ul>');
            items.forEach(function (item) {
                var $li = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>');
                $li.append($('<span></span>').text(item.code + ' - ' + item.label));
                var $remove = $('<button type="button" class="btn btn-sm btn-outline-danger js-review-cart-remove">&times;</button>');
                $remove.data('entity', entity);
                $remove.data('id', item.id);
                $li.append($remove);
                $list.append($li);
            });
            $section.append($list);

            var $approveBtn = $('<button type="button" class="btn btn-success btn-sm js-review-cart-approve-section">Approve ' + items.length + ' item(s)</button>');
            $approveBtn.data('entity', entity);
            $section.append($approveBtn);

            $body.append($section);
        });
    }

    function approveIds(entity, ids, onSuccess, onSettle) {
        var cfg = ENTITY_CONFIG[entity];
        if (!cfg || ids.length === 0) return;

        $.ajax({
            url: cfg.updateListUrl + '?ids=' + ids.join(','),
            method: 'POST',
            success: function (result) {
                if (result.status === 200) {
                    onSuccess();
                    notification('success', cfg.label + ' reviewed successfully', 'fa fa-check', 'Success');
                } else {
                    notification('danger', result.message || 'Failed to approve items', 'fa fa-warning', 'Error');
                }
                if (onSettle) onSettle();
            },
            error: function () {
                notification('danger', 'Failed to approve items', 'fa fa-warning', 'Error');
                if (onSettle) onSettle();
            }
        });
    }

    $(function () {
        renderBadge();
        syncButtonStates();

        $(document).on('click', '.js-add-to-review-cart', function () {
            var _this = $(this);
            var entity = _this.data('entity');
            if (!ENTITY_CONFIG[entity]) return;

            var cart = getCart();
            var id = _this.data('id');
            var alreadyIn = cart[entity].some(function (item) { return String(item.id) === String(id); });
            if (alreadyIn) {
                cart[entity] = cart[entity].filter(function (item) { return String(item.id) !== String(id); });
            } else {
                cart[entity].push({ id: id, code: _this.data('code'), label: _this.data('label') });
            }
            saveCart(cart);
            renderBadge();
            syncButtonStates();
        });

        $(document).on('click', '.js-open-review-cart', function () {
            renderCartBody();
            $('#reviewCartModal').modal('show');
        });

        $(document).on('click', '.js-review-cart-remove', function () {
            var _this = $(this);
            var entity = _this.data('entity');
            var id = _this.data('id');
            var cart = getCart();
            cart[entity] = (cart[entity] || []).filter(function (item) { return String(item.id) !== String(id); });
            saveCart(cart);
            renderBadge();
            syncButtonStates();
            renderCartBody();
        });

        $(document).on('click', '.js-review-cart-approve-section', function () {
            var _this = $(this);
            var entity = _this.data('entity');
            if (!ENTITY_CONFIG[entity]) return;

            var cart = getCart();
            var ids = (cart[entity] || []).map(function (item) { return item.id; });
            if (ids.length === 0) return;

            _this.prop('disabled', true);
            approveIds(entity, ids, function () {
                cart[entity] = [];
                saveCart(cart);
                renderBadge();
                syncButtonStates();
                renderCartBody();
            }, function () {
                _this.prop('disabled', false);
            });
        });

        $(document).on('click', '.js-direct-approve', function () {
            var _this = $(this);
            var entity = _this.data('entity');
            var id = _this.data('id');
            if (!ENTITY_CONFIG[entity]) return;

            if (!window.confirm('Set this item to Reviewed directly, without adding it to the review list?')) {
                return;
            }

            _this.prop('disabled', true);
            approveIds(entity, [id], function () {
                window.location.reload();
            }, function () {
                _this.prop('disabled', false);
            });
        });
    });
})();
