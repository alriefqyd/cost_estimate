$(function() {
    // var itemAdditionalSelectInit = function (el) {
    //     var _this = $(el);
    //     if (_this.data("select2")) _this.select2("destroy");
    //     _this.select2({
    //         minimumInputLength: 3,
    //         placeholder: "Please Select Item",
    //         allowClear: true,
    //         width: '100%',
    //         ajax: {
    //             url: _this.data('url'),
    //             data: function (params) {
    //                 return {
    //                     q: params.term,
    //                     type:_this.data('type')
    //                 }
    //             },
    //             processResults: function (resp) {
    //                 return {
    //                     results: resp
    //                 }
    //             },
    //         }
    //     })
    // }

    // var _selectItemAdditional = $('.js-select-item-additional');
    // $(document).on("change",".js-select-item-additional", function (){
    //     _selectItemAdditional.each(function () {
    //         itemAdditionalSelectInit(this);
    //     });
    // });

    // var _work_element = $('.js-select-work-element');
    // _work_element.each(function () {
    //     workElementSelectInit(this)
    // })


    // $(document).on('click','.js-add-work-item-additional', function (){
    //     var _this = $(this)
    //     var template = $('#js-template-table-work-item-additional-man-power').html();
    //     Mustache.parse(template);
    //     var data = {
    //         "no": _table_no += 1
    //     }
    //     var _temp = Mustache.render(template, data)
    //     $(this).siblings('table').find('tbody').append(_temp)
    //
    //     var _lastColumn = _this.closest('.js-modal-work-item').find('table tr:last')
    //     var _selectLast = _lastColumn.find('.js-select-item-additional');
    //     itemAdditionalSelectInit(_selectLast)
    // });
});
