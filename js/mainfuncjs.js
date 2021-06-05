$(document).ready(function () {
    $('#loader').hide();
	$("#add_studentcsv").on('submit', (function (e) {
		var value = jQuery.trim($("#file").val());
		var filename = $('input[type=file]').val().split('\\').pop();
		var pattern = "^.+\.(csv)$";

		if (!filename.match(pattern)) {
			alert("Only .csv format is allowed!! ");
			$("#file").focus();

			return false;
		}

		e.preventDefault();
		//$("#message").empty();
		$('#loader').show();
		$.ajax({
			url: "uploadcsv.php", // Url to which the request is send
			type: "POST", // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false, // The content type used when sending data to the server.
			cache: false, // To unable request pages to be cached
			processData: false,
			success: handleResult

		});
	}));

});


function handleResult(data) {
   $('#loader').hide();
	alert(data);
	location.reload();

}

function selectCountry(val) {
	$("#search-box").val(val);
	$("#suggesstion-box").hide();
}
/** Open Model start**/

function callone() {
	var fromDate = $("#fromDate").val();
	var toDate = $("#toDate").val();
	var compList = $("#compList").val();
	var flag = $("#flag").val();
	var dateReg = /^\d{4}([./-])\d{2}\1\d{2}$/;
	var obj = $("#cities").find("option[value='" + compList + "']");

	if (!fromDate.match(dateReg)) {
		$("#fromDate").focus();
		return false;
	}
	if (!toDate.match(dateReg)) {
		$("#toDate").focus();
		return false;
	}
	if (fromDate > toDate) {
		alert("From date should not be Greater then To date!!");
		$("#fromDate").focus();
		return false;

	}
	if (obj != null && obj.length > 0) {} else {
		alert("Enter Valid Company !!");
		$("#compList").focus();
		return false;

	}
	if (compList == "") {
		$("#compList").focus();
		return false;
	}
	var url = "statistics.php?fromDate=" + fromDate + "&toDate=" + toDate + "&compList=" + compList + "&flag=" + flag;
	window.open(url, '_blank');

	/*jQuery.ajax({
		type: 'post',
		data: {},
		success: function (response) {
			$("#statisticsModal iframe").attr("src", url);
			// Display Modal
			$('#statisticsModal').modal('show');
		}
	});
	*/
}