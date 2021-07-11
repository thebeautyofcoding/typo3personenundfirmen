
// $(document).ready(function () {
//     $(this).on('click', ':button.page-link', '#nextButton', '#previousButton', function () {







//         console.log($(this).val())

//         var currentPageNumber = $(this).val();

//         var controllerpath = $("#uri_hidden").val();
//         var ajaxPageLimit = $('#ajaxPageLimit').val()
//         console.log(currentPageNumber, ajaxPageLimit)
//         $.ajax({
//             type: "POST",
//             url: controllerpath,
//             data: { 'pageNumber': currentPageNumber, 'ajaxPageLimit': ajaxPageLimit },
//             success: function (response) {
//                 $('[id=tr]').each(function () {
//                     $(this).remove()
//                 })




//                 $('#defaultLimit').val(ajaxPageLimit)
//                 $('#defaultLimit').text(ajaxPageLimit)
//                 var tableRows = $(response).find('#tr')
//                 $('#trHeader').after(tableRows)
//                 console.log(tableRows)
//                 $('#paginationContainer').remove()

//                 var pagination = $(response).find('#paginationContainer')
//                 $('#table').after(pagination)

//             }
//         })





//     })
// })


