<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Niko Niko Team Tool</title>

    <!-- Bootstrap core CSS -->
    <link href="public/bootstrap3/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="css/SimpleCalendar.css" type="text/css" />
    <!-- Custom styles for this template -->
    <link href="public/bootstrap3/css/dashboard.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="public/bootstrap3/js/bootstrap.min.js"></script>
    <script src="public/bootstrap3/js/docs.min.js"></script>

	<script>
		$(document).ready(function() { 
			$("#team-selector").change(function() {
				$(this).parent("form").submit();
			});
			
			$("#action-selector").change(function() {
				$(this).parent("form").submit();
			});
			
			$("#datamode-selector").change(function() {
				$(this).parent("form").submit();
			});
			
			$("#check-trigger").click(function() {
			
				var datas = {};
				 $.ajax({  
					type: 'POST',        
					url: "index.php?controller=calendars&action=ajax-check",  
					data: datas,
					dataType: 'json',    
					success: function(resp) {
						if (resp['msg'] == 'OK') {
							console.log(resp);
							$("#check-trigger .badge").html(resp['new']);
						} else {
							console.log(resp);
							
						}
					}  
				  });
					
			});
			
			
			
			
		});
	
	
	
	</script>

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Niko-Niko Team Tool</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#" id="check-trigger">Actualiser <span class="badge"></span></a></li>
            <li><a href="#">Parametrage</a></li>
            
          </ul>
          
          <form class="navbar-form navbar-right" id="header-form">
            <input type="hidden" name="controller" value="calendars" />
           <input type="hidden" name="action" value="view" />
            <select class="form-control" name="id" id="team-selector">
            <? foreach (users::getTeams() as $tid => $tname): ?>
			
				<option value="<?=$tid?>" <? if ($tid == request::get('id')): ?>selected="selected" <? endif; ?> />
					<?=$tname?>
				</option>
			
			<? endforeach; ?>
			</select>
			
			<select class="form-control" name="datamode" id="datamode-selector">
           
           
				<option value="allvotes" <? if ("allvotes" == request::get('datamode')): ?>selected="selected" <? endif; ?> />
					Tous les votes
				</option>
			<option value="average" <? if ("average" == request::get('datamode')): ?>selected="selected" <? endif; ?> />
					Moyenne
				</option>
			
			</select>
			<select class="form-control" name="action" id="action-selector">
           
           
				<option value="view" <? if ("view" == request::get('action')): ?>selected="selected" <? endif; ?> />
					Calendrier
				</option>
			<option value="viewgraphic" <? if ("viewgraphic" == request::get('action')): ?>selected="selected" <? endif; ?> />
					Graphique
				</option>
			
			</select>
			
          </form>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        
        <div class="col-sm-12  main">

			
			
			<?=$template_content?>
	
        </div>
      </div>
    </div>
  </body>
</html>

