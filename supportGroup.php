<?php
if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
} else {
    $user = null;
}
?>

<p class='contentHeader'>Free Cancer Support Now Services. </p>
<p>
    <?php if($user) print "<input class='listitem-add' type='button' value='Add new group' onclick='showEditPanel();'/>"; ?>
</p>
<hr/>

<ul class='contenList'>
<?php
// Get data from db
require_once('getConnection.php');
$conn = getConnection();
$query = "SELECT * FROM ccs.csn_services ORDER BY title";
$result = pg_query($conn, $query);
$services = array();
// display result
while($row = pg_fetch_assoc($result)) {
    $services[$row['id']] = array('title'=> $row['title'], 'desc'=> $row['description']);

    $hasFlyer = $row['flyer_file_name'] ? true : false;
    print "<li id='group" . $row['id'] . "'><p class='listItemHeader' style='font-weight: bold'>" . $row['title'];
    if ($hasFlyer) {
        print " <span class='flyer'>(view <a href='#' onclick='window.open(\"pdfs/" . 
            $row['flyer_file_name'] . "\");'>flyer</a>)</span>";
    }
    // print manage buttons
    if ($user) {
        print " <input type='button' class='edit' value='Edit text' onclick='showEditPanel(" . $row['id'] . ");'/>"; 
        if ($hasFlyer) {
            print "<input type='button' value='Delete flyer' onclick='deleteFlyer(" . $row['id'] . ");'/>";
        } else {
            print "<input type='button' value='Add flyer' onclick='addFlyer(" . $row['id'] . ");'/>";
            print "<input type='file' id='flyer_upload_" . $row['id'] . "' name='flyer_upload_" . $row['id'] . 
                "' hidden onchange='uploadFlyer(this, " . $row['id'] . ");' accept='.pdf'/>";
        }
        print "<input type='button' value='Remove group' onclick='removeGroup(" . $row['id'] . ", \"" . $row['title'] . "\");'/>";
    }
    print "</p>";
    print str_replace("\r\n", "<br/>", $row['description']);
    print "</li>";
}
pg_close($conn);
?>
</ul>
<div id='edit_panel' style='font-size:small'></div>
<!--form action='remote/updateServiceGroup.php' method='post'>
<input name='group_title' size=70 placeholder='Add a title...' /><br/>
<textarea name='group_desc' cols=69 rows=7 placeholder='Add description...'></textarea><br/>
<input type='submit' class='button' value='Submit'/>
</form-->
<script>
var services = <?php print json_encode($services);?>;

function showEditPanel(id) {
    var title = '', desc = '';
    var html = "<form action='remote/updateServiceGroup.php' method='post'>";
    if (id) {
        html += "<input type='hidden' name='id' value=" + id + " />";
        title = services[id].title;
        desc = services[id].desc;
    }
    html += "<input name='group_title' size=70 value='" + title + "' placeholder='Add a title...' required /><br/>"
         + "<textarea name='group_desc' cols=69 rows=7 placeholder='Add description...' required>" + desc + "</textarea><br/>"
         + "<input type='submit' class='button' value='Submit'/>"
         + "<input type='button' value='Cancel' onclick='closeDialog();' />"
         + "</form>";
    jQuery('#edit_panel').html(html).dialog({
        modal: true,
        title: 'Edit a group',
        draggable: false,
        minWidth: 600
    });
}

function closeDialog() {
    jQuery('#edit_panel').dialog('close');
}

function addFlyer(id) {
    jQuery('#flyer_upload_' + id).click();
}

function uploadFlyer(el, id) {
    var formData = new FormData();
    formData.append('newFlyer', jQuery(el).prop('files')[0]);
    formData.append('id', id);
    jQuery.ajax({
        url: 'remote/uploadFlyer.php',
        type: 'post',
        processData: false,
        contentType: false,
        dataType: 'json',
        data: formData
    })
    .done(function(response) {
        if (response === 1) {
            location.reload();
        } else {
            jQuery(el).val('');
            alert(response);
        }
    });
}

function deleteFlyer(id) {
    if (confirm('Are you sure to delete this flyer?')) {
        jQuery.post(
            'remote/deleteFlyer.php',
            { 'id' : id },
            function (response) {
            console.log(response);
                if (response == 1) {
                    location.reload();
                } else {
                    alert('Failed to remove flyer!');
                }
            }
        );
    }
}

function removeGroup(id, title) {
    if (confirm('Are you sure to remove this group: ' + title + ' ?')) {
        jQuery.post(
            'remote/deleteGroup.php',
            { 'id' : id },
            function (response) {
                if (response == 1) {
                    // Remove group from page
                    jQuery('#group' + id).remove();
                } else {
                    alert('Failed to remove group!');
                }
            }
        )
        .fail(function() {
            alert('Failed to remove group!');
        });
    }
}
</script>
