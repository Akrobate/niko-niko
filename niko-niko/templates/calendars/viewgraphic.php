<? if ($datamode == "allvotes"  ): ?>
<div style="text-align:center">
	<img src="<?=url::internal('images','areagraphic',$team, '&periode=now') ?>" />

	<img src="<?=url::internal('images','areagraphic',$team, '&periode=1' ) ?>" />

	<img src="<?=url::internal('images','areagraphic',$team, '&periode=2' ) ?>" />

	<img src="<?=url::internal('images','areagraphic',$team, '&periode=3' ) ?>" />
</div>

<? else: ?>
<link rel="stylesheet" type="text/css" href="public/js/dist/jquery.jqplot.min.css" />
<script language="javascript" type="text/javascript" src="public/js/dist/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="public/js/dist/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="public/js/dist/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="public/js/dist/plugins/jqplot.dateAxisRenderer.min.js"></script>

<script>

$(document).ready(function(){
  
  /* example may be use full
  var line1=[['23-May-08', 578.55], ['20-Jun-08', 566.5], ['25-Jul-08', 480.88], ['22-Aug-08', 509.84],
      ['26-Sep-08', 454.13], ['24-Oct-08', 379.75], ['21-Nov-08', 303], ['26-Dec-08', 308.56],
      ['23-Jan-09', 299.14], ['20-Feb-09', 346.51], ['20-Mar-09', 325.99], ['24-Apr-09', 386.15]];
 */
 
   var line1=[
   		<? foreach($data as $date => $moyenne) : ?>
   			<?=("['". $date ."', " . $moyenne ."],") ?>
   		<? endforeach; ?>
   ];     
      
  var plot1 = $.jqplot('chart1', [line1], {
      title:'Graphique Niko-Niko',
      axes:{
        xaxis:{
          renderer:$.jqplot.DateAxisRenderer,
          tickOptions:{
            formatString:'%B&nbsp;%#d'
          }
        },
        yaxis:{
          tickOptions:{
            formatString:'%d'
            }
        }
      },
      highlighter: {
        show: true,
        sizeAdjust: 7.5
      },
      cursor: {
        show: false
      }
  });
});

</script>


<div id="chart1"> </div>
<? endif; ?>
