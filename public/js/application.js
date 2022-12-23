$(function(){
    /**
     * Project Form
     */
    $('.js-add-project-form').validate({
        rules:{
            project_no : {
                required:true,
            },
            project_title : {
                required:true
            },
            project_sponsor:{
                required:true
            },
            project_manager:{
                required:true
            },
            project_engineer:{
                required:true
            },
        },
        messages:{
            project_no: {
                remote:"Project no already taken"
            }
        }
    })

    var designEngineerInit = function (e){
        var _this = $(e);
        if(_this.data("select2")) _this.select2("destroy")
        _this.select2({
            allowClear:true,
            width:'100%',
            ajax:{
                url : _this.data('url'),
                data:function (params) {
                    return {
                        subject : _this.data('subject'),
                        q: params.term
                    }
                },
                processResults: function (resp) {
                    console.log(resp)
                    return {
                        results: resp
                    }
                }
            }
        })
    }

    $('.js-design-engineer').each(function (){
        designEngineerInit(this)
    })

    /**
     * Work Element Form
     */
    var _table_no = 0;
    $(document).on('click', '.js-add-work-element', function () {
        var template = $('#js-template-work-item').html();
        Mustache.parse(template);
        var data = {
            "no": _table_no += 1
        }
        var _temp = Mustache.render(template, data)
        $('.js-work-element-table').find('tbody').append(_temp)
    })

    $(document).on('click','.js-delete-work-element',function (){
        var _this = $(this);
        var _idx = _this.data('idx')

        _this.closest('.js-work-element-input-column').remove()
    })
})
