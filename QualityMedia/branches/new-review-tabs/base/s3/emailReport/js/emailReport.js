// Display data as either text or images
$(function(){
    // Display the text report
    $('#displayText').click(function(){
        $('#graphReport').hide();
        $('#textReport').show();
    });
    // Display the graph report
    $('#displayGraphs').click(function(){
        $('#textReport').hide();
        $('#graphReport').show();
    });
    // Set the datepicker to only display years and months
    $('.date').datepicker({
        format: "M-yyyy",
        viewMode: "months", 
        minViewMode: "months"
    });
    // Create the new URL based on the datepicker selections
    $("#EmailReport_dateStart, #EmailReport_dateEnd").change(function(){
        dateChangedVal = $(this).val();
        var year = $(this).val().substring(4,8);
        var day = $(this).val().substring(2,4);
        var month = $(this).val().substring(0,2);
        var a_href = $('#datePicker > div:nth-child(3) > a:nth-child(1)').attr('href');
        a_hrefLength = a_href.length;
        if ($(this).attr('id') == 'EmailReport_dateStart') {
            var startDateIndex = a_href.indexOf('startDate');
            var endDateIndex = a_href.indexOf('endDate');
            var endDateSubstring = a_href.substring(endDateIndex);
            var newUrl = a_href.substring(0, startDateIndex + 10) + year + month + day + '/' + endDateSubstring;
            $('#datePicker > div:nth-child(3) > a:nth-child(1)').attr('href', newUrl);
        } else {
            var startDateIndex = a_href.indexOf('startDate');
            var endDateIndex = a_href.indexOf('endDate');
            var startStringSubstring = a_href.substring(0, startDateIndex + 19);
            var newUrl = startStringSubstring + 'endDate/' + year + month + day + '.html';
            $('#datePicker > div:nth-child(3) > a:nth-child(1)').attr('href', newUrl);
        }
    });
    // Render the email body as WYSIHTML5
    if($('textarea.wys').length > 0){
        $('textarea.wys').wysihtml5({
        });
    }
    // Hide the WYSIHTML toolbar
    $('.wysihtml5-toolbar').attr('hidden', 'hidden');
});