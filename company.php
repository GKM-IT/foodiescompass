<?php define('IN_SCRIPT',1);
define('ADMIN_PATH','../');
include('functions/config.php');
@session_start();
ob_start();

error_reporting(E_ALL & ~E_NOTICE);
?>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="javascript/jquery-min.js"></script>
<script type="text/javascript" language="javascript" src="javascript/userspageheadercompany.js"></script>
<script type="text/javascript" language="javascript" src="javascript/sortTable.js"></script>
<script type="text/javascript" language="javascript" src="report_ mootools_details.js"></script>
<script type="text/javascript" language="javascript" src="reportpageheader.js"></script>
<script type="text/javascript" src="javascript/jquery.datepick.js"></script>

<script type="text/javascript">
function GetDate()
{

var fromDate=document.getElementById("from_date").value;
var ToDate=document.getElementById("to_date").value;

	$.ajax({ 
			url: 'company-list.php',
			type: 'POST',
			data : 'fromDate='+fromDate+'&ToDate='+ToDate,
			success : function(data){
			
			}
			});

}

</script>
<style type="text/css">
@import "style/jquery.datepick.css";</style> 
<script type="text/javascript">
var $ = jQuery.noConflict();
$(function() {	

	$('#from_date').datepick({dateFormat: 'dd-mm-yyyy', showTrigger: '<img src="images/calendar.gif" align="absmiddle" alt="Popup" style="padding-left:3px;">'});
	$('#to_date').datepick({dateFormat: 'dd-mm-yyyy', showTrigger: '<img src="images/calendar.gif" align="absmiddle" alt="Popup" style="padding-left:3px;">'});	
});
</script>


    <div id="search">
	  
		 <div class="acStretchOut" id="acStretcher_page27_0">
		  <div style=" padding-top:2px;">
	     <table width="681" border="0" align="center" cellpadding="0" cellspacing="0" >
           <tr>
             <td bgcolor="#B2BECF"><table width="60%" border="0" align="center" cellspacing="1" style="border-color: rgb(218, 218, 218); border-width: 0px; width: 100%;">                 
                   <tr>
                     <td height="25" align="left" bgcolor="#FFFFFF"><span class="labelbold">Search By  : </span>
                       <input name="keyword" id="keyword" type="text" class="mediumblack" size="30" onKeyUp="showPageHeader('1','25',this.value,'company_name','ASC','','','','');">
                       
                       <span class="smallblack"> (Date, Meeting Type, Company Name, Sector, Market Capitalization)</span></td>
                   </tr>                 
             </table></td>
           </tr>
         </table>
		 </div>
		 </div>
	  </div>
      
      <br clear="all" />
      
      
      <div id="search">
	  
		 <div class="acStretchOut" id="acStretcher_page27_0">
		  <div style=" padding-top:2px;">
	     <table width="681" border="0" align="center" cellpadding="0" cellspacing="0" >
           <tr>
             <td bgcolor="#B2BECF">
             <table width="60%" border="0" align="center" cellspacing="1" style="border-color: rgb(218, 218, 218); border-width: 0px; width: 100%;">                 
                   <tr>
                         <td width="13%" height="30" align="left" bgcolor="#FFFFFF"><span class="labelbold">Filter By  : </span>                         </td>
                         
                     <td width="39%" height="30" align="left" bgcolor="#FFFFFF">
                      
                       <select id="meeting_type_filter" name="meeting_type_filter" class="mediumblack" onchange="showPageHeader('1','25','','company_name','ASC',this.value,'','','');">
                         <option  class="mediumblack" value="">Select Meeting Type</option>
                         <?php 
						 $sqlf=mysql_query("SELECT meeting_type,advisory_id FROM tbladvisory WHERE status=1 GROUP BY meeting_type");
						 while($rowf=mysql_fetch_assoc($sqlf))
						 {
						 $meeting_typef=$rowf['meeting_type'];
						 $advisory_idf=$rowf['advisory_id'];
						 ?>
                         <option  class="mediumblack" value="<?php echo($meeting_typef);?>"><?php echo($meeting_typef); ?></option>
                         <?php } ?>
                 </select>
                         </td>
                         
                     <td width="48%" height="30" align="left" bgcolor="#FFFFFF">
                     
                       <select id="market_capitalization_filter" name="market_capitalization_filter" class="mediumblack" onchange="showPageHeader('1','25','','company_name','ASC','',this.value,'','');">
                         <option class="mediumblack" value="">Select Market Capitalization</option>
                         <?php 
						 $sqlc=mysql_query("SELECT market_capitalization,advisory_id FROM tbladvisory WHERE status=1 GROUP BY market_capitalization");
						 while($rowc=mysql_fetch_assoc($sqlc))
						 {
						 $market_capitalizationc=$rowc['market_capitalization'];
						 $advisory_idc=$rowc['advisory_id'];
						 ?>
                         <option class="mediumblack" value="<?php echo($market_capitalizationc);?>"><?php echo($market_capitalizationc); ?></option>
                         <?php } ?>
                       </select>
                     </td>
               </tr>                 
             </table>
             </td>
           </tr>
         </table>
		 </div>
		 </div>
	  </div>
      
     <br clear="all" />  
      
      <div id="search">
	  
		 <div class="acStretchOut" id="acStretcher_page27_0">
		  <div style=" padding-top:2px;">
	     <table width="681" border="0" align="center" cellpadding="0" cellspacing="0" >
           <tr>
             <td bgcolor="#B2BECF">
             <table width="60%" border="0" align="center" cellspacing="1" style="border-color: rgb(218, 218, 218); border-width: 0px; width: 100%;">                 
                   <tr>
                         <td width="15%" height="30" align="left" bgcolor="#FFFFFF"><span class="labelbold">From Date  : </span> </td>
                         
                     <td width="17%" height="30" align="left" bgcolor="#FFFFFF">
                       <input name="from_date" id="from_date" type="text" class="mediumblack" size="10" />
                     </td>
                     <td width="12%" height="30" align="left" bgcolor="#FFFFFF"><span class="labelbold">To Date  : </span></td>
                     <td width="25%" height="30" align="left" bgcolor="#FFFFFF">
                       <input name="to_date" id="to_date" type="text" class="mediumblack" size="10" />
                     </td>
                     <td width="31%" height="30" align="left" bgcolor="#FFFFFF"><input name="submit" id="submit" type="button" value="Submit" onclick="showPageHeader('1','25','','Company Name','ASC','','',document.getElementById('from_date').value,document.getElementById('to_date').value);"  />
                     </td>
               </tr>                 
             </table>

             </td>
           </tr>
         </table>
		 </div>
		 </div>
	  </div>
      
     <br clear="all" />  

 <div id="userlist" style="height:370px;width:100%;" align="center">&nbsp;</div>
 
 <script type="text/javascript">
showPageHeader('1','25','','meeting_date','DESC','','',document.getElementById('from_date').value,document.getElementById('to_date').value);
</script> 

