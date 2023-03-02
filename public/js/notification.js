function notification(type,message,icon,status){
    type = type ? type : 'theme'
    icon = icon ? icon : 'fa fa-check'
    status = status ? status : 'Success'

    var notify = $.notify('<i class="fa fa-bell-o"></i><strong>Loading...</strong>', {
        type: type,
        allow_dismiss: true,
        delay: 5000,
        showProgressbar: true,
        timer: 300
    });

    setTimeout(function() {
        notify.update('message', '<i class="'+icon+'"></i><strong>'+status+' |</strong> '+ message);
    }, 1000);
}

