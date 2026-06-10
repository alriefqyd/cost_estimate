$(function () {
    var $wrap    = $('#js-notif-wrap');
    var $btn     = $('#js-notif-btn');
    var $badge   = $('#js-notif-badge');
    var $list    = $('#js-notif-list');
    var $markAll = $('#js-notif-mark-all');

    if ($wrap.length === 0) return;

    var loaded = false;

    function renderNotifications(data) {
        var items = data.notifications;
        var count = data.unread_count;

        // Badge
        if (count > 0) {
            $badge.text(count > 99 ? '99+' : count).removeClass('d-none');
            $btn.addClass('has-unread');
        } else {
            $badge.addClass('d-none');
            $btn.removeClass('has-unread');
        }

        // List
        $list.empty();
        if (items.length === 0) {
            $list.append('<li class="notif-empty"><i class="fa fa-bell-slash-o" style="font-size:24px;display:block;margin-bottom:8px;"></i>No notifications</li>');
            return;
        }

        $.each(items, function (i, n) {
            var d       = n.data;
            var isUnread = !n.read_at;
            var iconBg  = isUnread ? 'rgba(46,117,182,.12)' : '#f3f4f6';
            var iconColor = d.color || '#2e75b6';
            var icon    = d.icon || 'fa-bell';

            var $item = $('<li>').addClass('notif-item' + (isUnread ? ' unread' : ''))
                .attr('data-id', n.id)
                .attr('data-url', d.url || '#');

            $item.append(
                $('<div class="notif-icon">').css({ background: iconBg, color: iconColor })
                    .html('<i class="fa ' + icon + '"></i>')
            );

            var $body = $('<div class="notif-body">');
            $body.append($('<p class="notif-msg">').text(d.message || ''));
            $body.append($('<span class="notif-time">').text(n.created_at));
            $item.append($body);

            if (isUnread) {
                $item.append('<div class="notif-unread-dot"></div>');
            }

            $list.append($item);
        });
    }

    function loadNotifications() {
        $.getJSON('/notifications', function (data) {
            renderNotifications(data);
            loaded = true;
        });
    }

    // Toggle dropdown
    $btn.on('click', function (e) {
        e.stopPropagation();
        var isOpen = $wrap.hasClass('open');
        $wrap.toggleClass('open');
        if (!isOpen && !loaded) {
            loadNotifications();
        }
    });

    // Close on outside click
    $(document).on('click', function (e) {
        if (!$wrap.is(e.target) && $wrap.has(e.target).length === 0) {
            $wrap.removeClass('open');
        }
    });

    // Click item → mark read then navigate
    $list.on('click', '.notif-item', function () {
        var $item = $(this);
        var id    = $item.data('id');
        var url   = $item.data('url') || '#';

        if ($item.hasClass('unread')) {
            $.post('/notifications/' + id + '/read', { _token: $('meta[name="csrf-token"]').attr('content') }, function () {
                $item.removeClass('unread');
                $item.find('.notif-unread-dot').remove();
                var current = parseInt($badge.text()) || 0;
                var next = Math.max(0, current - 1);
                if (next === 0) {
                    $badge.addClass('d-none');
                    $btn.removeClass('has-unread');
                } else {
                    $badge.text(next);
                }
            });
        }

        if (url && url !== '#') {
            window.location = url;
        }
    });

    // Mark all read
    $markAll.on('click', function () {
        $.post('/notifications/read-all', { _token: $('meta[name="csrf-token"]').attr('content') }, function () {
            $list.find('.notif-item').removeClass('unread');
            $list.find('.notif-unread-dot').remove();
            $list.find('.notif-msg').css('font-weight', '');
            $badge.addClass('d-none');
            $btn.removeClass('has-unread');
        });
    });

    // Initial badge load (unread count only, no full list yet)
    $.getJSON('/notifications', function (data) {
        var count = data.unread_count;
        if (count > 0) {
            $badge.text(count > 99 ? '99+' : count).removeClass('d-none');
            $btn.addClass('has-unread');
        }
        loaded = false; // force full re-render on first open
    });
});
