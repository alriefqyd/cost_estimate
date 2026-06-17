$(function () {
    if ($('.db-wrap').length === 0) return;

    // Animate KPI progress bars on load
    $('.kpi-progress-bar').each(function () {
        var target = $(this).data('width') || $(this).attr('style').match(/width:\s*([\d.]+)%/)?.[1] || 0;
        $(this).css('width', 0).animate({ width: target + '%' }, 800);
    });
});
