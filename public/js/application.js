$(function(){

    $('.select2').select2();

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
                    };
                },
                processResults: function (resp) {
                    return {
                        results: resp
                    };
                }
            }
        })
    }

    $('.js-design-engineer').each(function (){
        designEngineerInit(this)
    })
})
