@extends('admin.body.header')
@section('admin')

<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-md-4">
            <form action="{{route('dept.add')}}" id="deptForm" method="post">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="card">
                    <div class="card-header">
                        Department
                    </div>
                    <div class="card-body">
                        <label for="">Department Name</label>
                        <input type="text" name="dept_name" id="dept_name" class="form-control">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-end">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Departments
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-info">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Departments</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($depts as $dept)
                        <tr>
                            <td class="text-center">{{$i++}}</td>
                            <td>{{$dept->dept_name}}</td>
                            <td class="text-center">
                                <button type="button" value="{{ $dept->id }}" id="editDept" class="btn btn-outline-warning"><i class="fa-solid fa-pen-to-square"></i></button>
                                <a href="{{route('dept.delete', $dept->id)}}" id="delete" class="btn btn-outline-danger"><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('click', '#editDept', function(){
            var dept_id = $(this).val();

            $.ajax({
                type: "GET",
                url: "/admin/departments/edit/" + dept_id,
                success:function(res){
                    $("#dept_name").val(res.dept.dept_name);
                    $("#id").val(dept_id);
                    $("#deptForm").attr("action", "{{ route('dept.update') }}");
                }
            })
        });
    });
</script>
@endsection