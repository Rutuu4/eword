var arraydatacount = 0;
var popupcontactlist = Array();

var viewname = $('#path_comman').val();

$(document).ready(function(){
  $('#searchtext').keyup(function(event) 
  {
    if (event.keyCode == 13) {
      search_data('changesearch',popupcontactlist);
    }
  });
});

/// Pagination change
$('body').on('click','#common_tb a',function(e)
{
  $.ajax({
    type: "POST",
    url: $(this).attr('href'),
    data: {
      result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
    },
    success: function(html)
    {
      $("#common_div").html(html);
      try
      {
        for(i=0;i<popupcontactlist.length;i++)
        {
          $('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
        }
      }
      catch(e){}
    }
  });
  return false;
});

/// Select all records
$('body').on('click','#selecctall',function(e){
  if(this.checked) { 
    $('.mycheckbox').each(function() { 
      this.checked = true;
      var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
      if(arrayindex == -1)
      {
        popupcontactlist[arraydatacount++] = parseInt(this.value);
      }             
    });
  }
  else
  {
    $('.mycheckbox').each(function() { 
      this.checked = false;
      var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
      if(arrayindex >= 0)
      {
        popupcontactlist.splice( arrayindex, 1 );
        arraydatacount--;
      }
    });        
  }
  $("#cnt_selected").text(popupcontactlist.length + " Record(s) Selected");
});
    
		
$('#allcheck').click(function(){
  var val = $('#submit_actions').val();
  if(val != '')
	{
    
    perform_actions(val,viewname, popupcontactlist);	

    arraydatacount = 0;
    popupcontactlist = Array();
	}
	else
	{
    $.alert({
        title: 'Alert!',
        content: "Please select atleast one operation to continue.",
        buttons: {
          confirm:{btnClass:'hide'},
          cancel:{text:'ok'},
        },
    });
	}    
});
    
    
function search_data(allflag, popupcontactlist)
{ 
  var uri_segment = $("#uri_segment").val();
  var type = $("#type").val();

  $.ajax({
    type: "POST",
    url: viewname + uri_segment,
    data: {
      result_type: 'ajax', searchreport: $("#searchreport").val(), perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), type:type, allflag: allflag
    },
    success: function(html) {
      $("#common_div").html(html);
      try
      {
        for(i=0;i<popupcontactlist.length;i++)
        {
          $('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
        }
      }
      catch(e){}
    }
  });
  return false;
}

function delete_record(id, name)
{
  ini_msg = "Are you sure want to delete record? If you choose yes then it will delete all associated records also.";
  final_msg = 'Record deleted successfully.';
  msg_type = 'danger';

  $.confirm({
      content: ini_msg,
      buttons: {
          confirm: {
            text: 'Yes',
            action: function(){
              $.ajax({
                type: "POST",
                url: viewname + "ajax_delete_all",
                dataType: 'json',
                async: false,
                data: {'single_remove_id': id},
                success: function(data) {
                $.ajax({
                  type: "POST",
                  url: viewname + data,
                  data: {
                    result_type: 'ajax', searchreport: $("#searchreport").val(), perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: ''
                  },
                  success: function(html){
                    $("#common_div").html(html);
                    $("#cnt_selected").text("0 Record(s) Selected");
                    arraydatacount = 0;
                    popupcontactlist = Array();
                    $.notify({ message: final_msg },{ type: msg_type, });
                  }
                });
                return false;
                }
              });
            }
          },
      },
  });
}

function perform_actions(val,viewname,popupcontactlist)
{
  var status = '';
  var boxes = $('input[name="check[]"]:checked');
  if (boxes.length == '0')
  {
      $.alert({
        title: 'Alert!',
        content: "Please select record(s) to "+val+".",
        buttons: {
          confirm:{btnClass:'hide'},
          cancel:{text:'ok'},
        },
      });
      $('#selecctall').focus();
      return false;

  }

  if (val == 'delete')
  {
      var url = viewname + "ajax_delete_all";
      ini_msg = "Are you sure want to delete records? If you choose yes then it will delete all associated records also.";
      final_msg = 'Records deleted successfully.';
      msg_type = 'danger';
  }
  if (val == 'publish')
  {
      var url = viewname + "ajax_status_all";
      ini_msg = 'Are you sure want to make the records active?';
      final_msg = 'Records published successfully.';
      msg_type = 'success';
      status = '1';
  }
  if (val == 'unpublish')
  {
      var url = viewname + "ajax_status_all";
      ini_msg = 'Are you sure want to make the records inactive?';
      final_msg = 'Records unpublished successfully.';
      msg_type = 'warning';
      status = '0';
  }
  $.confirm({
      content: ini_msg,
      buttons: {
          confirm: {
            action: function(){
              var myarray = new Array;
              var i = 0;
              var boxes = $('input[name="check[]"]:checked');
              $(boxes).each(function() {
                  myarray[i] = this.value;
                  i++;
              });
              
              $.ajax({
                  type: "POST",
                  url: url,
                  dataType: 'json',
                  async: false,
                  data: {'myarray': popupcontactlist, status: status},
                  success: function(data) {
                      $.ajax({
                          type: "POST",
                          url: viewname + "/" + data,
                          data: {
                              result_type: 'ajax', searchreport: $("#searchreport").val(), perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: ''
                          },
                          success: function(html) {
                          $("#cnt_selected").text("0 Record(s) Selected");
                            arraydatacount = 0;
                            popupcontactlist = Array();
                            $('#submit_actions').val('');
                            $("#common_div").html(html);
                            $.notify({ message: final_msg },{ type: msg_type, });
                          }
                      });
                      return false;
                  }
              });
            }
          },
      },
  });
}

function clearfilter_data()
{
  $("#searchtext").val("");
  $("#type").val("");
  applysortfilte_contact('','');
}

function changepages()
{
    search_data('');
}

function applysortfilte_contact(sortfilter, sorttype)
{
  $("#sortfield").val(sortfilter);
  $("#sortby").val(sorttype);
  search_data('changesorting');
}

function status_change(status,id) 
{
  var path='';

  if(status == 0)
  {
      path = viewname+"unpublish_record/"+id;
      ini_msg = 'Are you sure want to make the record inactive?';
      final_msg = 'Record unpublished successfully.';
      msg_type = 'warning';
  }
  else
  {
      path = viewname+"publish_record/"+id;
      ini_msg = 'Are you sure want to make the record active?';
      final_msg = 'Record published successfully.';
      msg_type = 'success';
  }
  $.confirm({
      content: ini_msg,
      buttons: {
          confirm: {
            action: function(){
              $.ajax({
              type: "POST",
              url: path,
              dataType: 'json',
              success: function(result){
                  $.ajax({
                      type: "POST",
                      url: viewname+result,
                      data: {
                      result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
                      },
                      success: function(html){
                          $("#common_div").html(html);
                          $("#cnt_selected").text("0 Record Selected");
                          arraydatacount = 0;
                          popupcontactlist = Array();
                          $.notify({ message: final_msg },{ type: msg_type, });
                      }
                  });
                  return false;
                }
              });
            }
          },
      },
  });
}

function approve_status(status,id) 
{
  var path='';

  if(status == 0)
  {
      path = viewname+"unapprove_record/"+id;
      ini_msg = 'Are you sure want to unapprove the record?';
      final_msg = 'Record unapproved successfully.';
      msg_type = 'warning';
  }
  else
  {
      path = viewname+"approve_record/"+id;
      ini_msg = 'Are you sure want to approve the record?';
      final_msg = 'Record unapproved successfully.';
      msg_type = 'success';
  }
  $.confirm({
      content: ini_msg,
      buttons: {
          confirm: {
            action: function(){
              $.ajax({
              type: "POST",
              url: path,
              dataType: 'json',
              success: function(result){
                  $.ajax({
                      type: "POST",
                      url: viewname+result,
                      data: {
                      result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
                      },
                      success: function(html){
                          $("#common_div").html(html);
                          $("#cnt_selected").text("0 Record Selected");
                          arraydatacount = 0;
                          popupcontactlist = Array();
                          $.notify({ message: final_msg },{ type: msg_type, });
                      }
                  });
                  return false;
                }
              });
            }
          },
      },
  });
} 

$('body').on('click','.mycheckbox',function(e)
{ 
  if($('.mycheckbox:checkbox[value='+parseInt(this.value)+']:checked').length)
  {   
    var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
    if(arrayindex == -1)
    {       
      popupcontactlist[arraydatacount++] = parseInt(this.value);
    }

      if ($('.mycheckbox:checked').length == $('.mycheckbox').length) {
          $('#selecctall').prop('checked', true); // Checks it   
      }
  }
  else
  {
    var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
    if(arrayindex >= 0)
    {
      popupcontactlist.splice( arrayindex, 1 );
      $('#selecctall').prop('checked', false); // Checks it   
      arraydatacount--;
    }
  }
  $("#cnt_selected").text(popupcontactlist.length + " Record(s) Selected");
});
    

function remove_selection()
{
  var cnt = popupcontactlist.length;
  for(i=0;i<popupcontactlist.length;i++)
  {
    $('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',false);
  }
  $('#selecctall').attr('checked',false);
  popupcontactlist = Array();
  $("#cnt_selected").text("0 Record(s) Selected");
  arraydatacount = 0;
}