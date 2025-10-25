$(document).ready(function () {
  var borrowersTable = $("#borrowersTable").DataTable({
    ordering: false,
    searching: true,
  });
  var loansTable = $("#loansTable").DataTable({
    ordering: false,
    searching: true,
  });
  var paymentsTable = $("#paymentsTable").DataTable({
    ordering: false,
    searching: true,
  });

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex){
    if (
      settings.nTable.id !== "borrowersTable" && 
      settings.nTable.id !== "loansTable" &&
      settings.nTable.id !== "paymentsTable"
    ){
      return true;
    }

    var selectedBorrowerDept = $("#borrowerDept").val() || "";  
    var selectedBorrowerStatus = $("#borrowerStatus").val() || "";  
    var selectedloansPlans = $("#loansPlans").val() || "";  
    var selectedloansStatus = $("#loansStatus").val() || "";  
    var selectedPaymentPlans = $("#paymentPlans").val() || "";  

    var borrowerDept = data[4] || "";
    var borrowerStatus = data[5] || "";
    var loansPlans = data[2] || "";
    var loansStatus = data[5] || "";
    var paymentPlans = data[2] || "";

    if (
      (selectedBorrowerDept === "" || borrowerDept.includes(selectedBorrowerDept)) &&
      (selectedBorrowerStatus === "" || borrowerStatus.includes(selectedBorrowerStatus)) &&
      (selectedloansPlans === "" || loansPlans.includes(selectedloansPlans)) &&
      (selectedloansStatus === "" || loansStatus.includes(selectedloansStatus)) &&
      (selectedPaymentPlans === "" || paymentPlans.includes(selectedPaymentPlans))
    ){
      return true;
    }
    return false;
  });

  $("#borrowerDept, #borrowerStatus, #loansPlans, #loansStatus, #paymentPlans").on("change", function(){
    borrowersTable.draw();
    loansTable.draw();
    paymentsTable.draw();
  });
});