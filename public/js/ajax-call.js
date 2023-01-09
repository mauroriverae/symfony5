
function meGusta(id) {
    let ruta = Routing.generate('likes');
    //comunicamos el frot con el backend
    $.ajax({
        type: 'POST',
        url: ruta,
        data: ({id: id}),
        async: true, 
        dataType: "json",
        success: function (data){
            console.log(data['lokes']);
        }
    })
}