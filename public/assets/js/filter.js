$(document).ready(function () {
  $("#filterTable").DataTable({
    ordering: false,
    searching: true,
  });

  var table = $("#filterTable").DataTable();
  var statusIndex = 4;
  var deptIndex = 4;
  var planIndex = 2;

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== "filterTable") {
      return true;
    }
    var selectedStatus = $("#fStatus").length ? $("#fStatus").val() : "";
    var selectedDept = $("fDept").length ? $("#fDept").val() : "";
    var selectedPlan = $("#fPlan").length ? $("#fPlan").val() : "";

    var status = data[statusIndex];
    var dept = data[deptIndex];
    var plan = data[planIndex];

    if (
      (selectedStatus === "" || status.includes(selectedStatus)) &&
      (selectedDept === "" || dept.includes(selectedDept)) &&
      (selectedPlan === "" || plan.includes(selectedPlan))
    ) {
      return true;
    }
    return false;
  });

  $("#fStatus, #fDept, #fPlan").change(function () {
    table.draw();
  });
});
