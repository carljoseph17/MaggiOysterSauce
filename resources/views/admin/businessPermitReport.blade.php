<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Barangay Permit Report</title>

    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        @include('admin.nav')
        

        <div id="page-wrapper" style="padding-top: 0% ">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Issued Business Permit Report</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row" style="padding-bottom: 5%;">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Business Permit
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group col-md-4">
                                <label for="InputStart">Start Date</label>
                                <input type="date" id="dateStart" min="1954-10-01" max="<?php echo date('Y-m-d');?>" class="form-control" required />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="InputStart">End Date</label>
                                <input type="date" id="dateEnd" min="1954-10-01" max="<?php echo date('Y-m-d');?>" class="form-control" required />
                            </div>
                            <div class="form-group pull-right">
                            <label for="InputStart">Filter</label>
                                <div class="dropdown">
                                  <button class="btn btn-info dropdown-toggle" type="button" id="filter" data-toggle="dropdown"
                                    aria-haspopup="true" 
                                    aria-expanded="true">
                                    --
                                    Select--
                                    <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="selectFilter">
                                        <li id="all" value="All"><a href="#">All</a></li>
                                  </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pull-right">
                                <button type="button" id="generate" class="btn btn-warning" >Generate</button>
                            </div>
                    </div>
                    <hr>
                    <!-- /.panel-body -->
                    <div class="panel-body">
                    <h3 class="title">Business Permit Report: </h3>
                           <table width="100%" class="table table-hover mails m-0 table table-actions-bar" id="permit">
                               <thead>
                                  <tr>
                                    <th>Permit ID</th>
                                    <th>Name</th>
                                    <th>Business Name</th>
                                    <th>Business Kind</th>
                                    <th>Date Issued</th>
                                    <th>Date Expired</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr> 
                            </tbody>
                           </table>
                           <button type="submit" class="btn btn-danger btn-small btn pull-right">
                            <span class = "glyphicon glyphicon-print"> Print</span>
                           <!-- /.table-responsive -->
                       </div>
                       <!-- /.panel-body -->
                </div>
            </div>        
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    

    <!-- Morris Charts JavaScript -->
    <script src="../assets/raphael/raphael.min.js"></script>
    <script src="../assets/morrisjs/morris.min.js"></script>
    <script src="../assets/data/morris-data.js"></script>
    <script>
      $('#generate').click(function() {
        var dateEnd = $('#dateEnd').val();
        var dateStart = $('#dateStart').val();

          if(dateStart > dateEnd){
            sweetAlert({
                  title:'ERROR!!!',
                  text: 'The end date can not be less than the start date',
                  type:'error'
            },function(isConfirm){
                  $('#dateEnd').val("");
            });
          }else if(dateEnd == "" && dateStart == ""){
            sweetAlert({
                  title:'ERROR!!!',
                  text: 'Input Date!',
                  type:'error'
            },function(isConfirm){
                  $('#dateEnd').val("") ;
            });
          }else if(dateEnd == "" || dateStart == ""){
            sweetAlert({
                  title:'ERROR!!!',
                  text: 'Input Date!',
                  type:'error'
            },function(isConfirm){
                  $('#dateEnd').val("") ;
            });
          }
      })
    </script>
    <script>
        $selectFilter = $('#selectFilter li'),
        $liYear = $selectFilter.find('li');

        var counter=0;

         $selectFilter.click(function() {
            if(counter>-1)
            {
                $('#permit').dataTable().fnClearTable();
            }
            counter++;;
           var report = $(this);
            $('#filter').html(report.text()+' <span class="caret"></span>');
            $('.title').html('Business Permit Report: '+ report.text());
           var reports= report.text();

           $.ajax({
             method: 'get',
             url: '{{ URL::route("returnPermitReport")}}',
             dataType:'json',
             data: {
                 permit: reports,
             },
             success:function(data){
               for(i=0; i< data.permit.length; i++){
                if(data.permit.length != 0){
                  $('#permit').dataTable().fnAddData([
                      data.permit[i]['permit_id'],
                      data.permit[i]['name'],
                      data.permit[i]['business_name'],
                      data.permit[i]['business_kind'],
                      data.permit[i]['date_issued'],
                      data.permit[i]['date_expired'],
                      ]);
                }else{
                  $('.myTable').append('<center>No data available</center>');
                }
              }

             }
           })
         }) 
    </script>
    <script type="text/javascript">
      $(document).ready(function(){
          $(document).ready(function() {
            var t = $('#permit').DataTable({
                responsive: true,
                searchHighlight: true,
                "columnDefs": [
                    { 
                      "sortable" : false, 
                      "searchable": false,
                      "targets": 0
                    }
                ],
                "order": [[ 1, 'asc' ]]
            });
            t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });
      });
    </script>

</body>

</html>
