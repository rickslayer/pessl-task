$(function(){
    $('#btnSave').on('click', () => {
        let userEmail = $("#userEmail").val();
        let battery = $("#battery").val();
        let rh = $("#rh").val();
        let air = $("#air").val();
        let dw = $("#dw").val();

        $.ajax({
            url: 'http://localhost:8001/api/user',
            type: "POST",
            crossDomain: true,
            data: {
                userEmail,
                battery,
                rh,
                air,
                dw
            }
        })
        .done((response) => {
            console.log(response);
        });
    });
});