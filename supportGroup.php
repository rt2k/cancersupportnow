<?php
if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
} else {
    $user = null;
}
?>

<p class='contentHeader'>Free Cancer Support Now Services. </p>
<p>
    <i>* New groups transitioned over from People Living Through Cancer (PLTC) as of April/May 2016.</i>
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
    html += "<input name='group_title' size=70 value='" + title + "' placeholder='Add a title...' /><br/>"
         + "<textarea name='group_desc' cols=69 rows=7 placeholder='Add description...'>" + desc + "</textarea><br/>"
         + "<input type='submit' class='button' value='Submit'/>"
         + "</form>";
    jQuery('#edit_panel').html(html).dialog({
        modal: true,
        title: 'Edit a group',
        draggable: false,
        minWidth: 600
    });
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
<ul class='contentList'>

<!--li><p class='listItemHeader'>Advanced Diagnosis Group *</p>
All types of cancer<br/>
1st and 3rd Tuesday at 1:00PM <br/>
Carlisle & Comanche
</li>
<li><p class='listItemHeader'>Blood Cancer Group *</p>
For survivors dealing with a blood or lymphatic cancer <br/>
2nd and 4th Tuesday, 1:00-2:30PM <br/>
Montano, West of Fourth St.
<br/>
<div style='font-size:small;'>
Facilitators: Mary Josephson and Alex Klebenow<br/>
Location: North Valley<br/>
&nbsp;&nbsp;Time: 1-2:30 on the 2nd and 4th Tuesday of the month (starting May 10th)<br/>
General information: We are a support group for blood and lymph cancers. We welcome the newly diagnosed, those in
 treatment, and long term survivors.<br/>
&nbsp;&nbsp;"Don't walk behind me, I may not lead. Don't walk in front of me, I may not follow. Just walk beside me and be my
  friend. " Albert Camus
</div>
</li>
<li><p class='listItemHeader'>Breast Cancer Group *</p>
Every Wednesday 6:00-7:30PM <br/>
Carlisle & Comanche
</li>
<li><p class='listItemHeader'>Community Cancer Navigation
(view <a href='#' onclick='window.open("pdfs/community_cancer_navigation.pdf");'/>flyer</a>)</p>
Community cancer navigators are here to work hand in hand to meet the non-medical needs of
people experiencing cancer in any way.<br/>
<u>English speaking</u>: <br/>
Eleanor Schick: 255-0405 <br/>
<u>En Espanol</u>: <br/>
Sarah Contreras: (505)738-8171 
</li>
<li><p class='listItemHeader'>Coloring & Creativity *</p>
All cancers, survivors and caregivers <br/>
Temporarily Inactive
</li>
<li><p class='listItemHeader'>Friends and Family Writing Together
(view <a href='#' onclick='window.open("pdfs/journaling_support_group_for_friend.pdf");'/>flyer</a>)</p>
Journaling Support Group for Grief or Anticipatory Grief <br/>
For caregivers/loved ones of someone with any type of cancer <br/>
Every Thursday, 4:00PM to 5:30PM <br/>
UNM Cancer Center
</li>
<li><p class='listItemHeader'>LGBT Group *
(view <a href='#' onclick='window.open("pdfs/LGBT.pdf");'/>flyer</a>)</p>
CSN now hosts the ONLY LGBT Cancer Survivor/Caregiver group in NM<br/>
All diagnoses, cancer survivors <br/>
1st Tuesday of month, 6:30-8:30 PM <br/>
NM Cancer Center (Jefferson & Lang)<br/>
<br/>
<div style='font-size:small;'>
This group provides a safe and welcoming environment for adult LGBT Cancer Survivors
and Patients at any stage in their journey with cancer.  Their caregivers are welcome, as
are LGBT caregivers of others going through cancer. Meet with others to discuss the
effects of cancer on our lives and relationships.  info@cancersupportnow.org 505-255-0405
</div>
</li>
<li><p class='listItemHeader'>North Valley Women's Support Group</p>
All cancers, survivors and caregivers <br/>
Every other Thursday night, 6:30 PM to 8:30 PM <br/>
Montano, West of Fourth St.
</li>
<li><p class='listItemHeader'>One-on-One Cancer Caregiver Session</p>
One time, 90-minute Session: Resources & Support for Cancer Caregivers <br/>
Scheduled individually to accommodate the needs of the caregiver <br/>
Call Patricia at 505-307-3414
</li>
<li><p class='listItemHeader'>One on One Peer Cancer Support</p>
Survivors or caregivers <br/>
Call our Helpline at 505-255-0405 or Toll Free at 855-955-3500 <br/>
Seven days a week, 9:00 AM to 9:00 PM
</li>
<li><p class='listItemHeader'>One-on-one Peer matching</p>
Available through Helpline at 505-255-0405 or Toll Free at 855-955-3500 <br/>
Matching with a phone buddy who has dealt with a similar diagnosis and/or challenges
</li>
<li><p class='listItemHeader'>Ovarian Open Arms</p>
Third Saturday of the month, 10:30 AM <br/>
Covenant Presbyterian Church <br/>
NE Heights
</li>
<li><p class='listItemHeader'>Pueblo of Isleta Community Cancer Support</p>
All are welcome! <br/>
2nd Tuesday of the month, 10:30-Noon <br/>
Isleta Health Clinic
</li>
<li><p class='listItemHeader'>Relaxation Support Classes</p>
Open to cancer survivors and/or their loved ones <br/>
Call Jean Stouffer, certified hypnotherapist, 296-8423 <br/>
10:30-12:00 noon last Friday of the month <br/>
Carlisle Blvd NE
</li>
<li><p class='listItemHeader'>Sandia Breast Cancer Group</p>
1st and 3rd Tuesday of the month, <br/>
12:00 Noon to 1:00 PM <br/>
Sandia Base: Sandia Employees Only <br/>
</li>
<li><p class='listItemHeader'>Santa Fe Women's Support Group, "Surviving Sisters" *</p>
A group for all diagnoses <br/>
2nd and 4th Tuesday, 4:00-5:30PM <br/>
Santa Fe 
</li>
<li><p class='listItemHeader'>Survivors Writing Together (view <a href='#' onclick='window.open("pdfs/monday_writing_group.pdf");'>flyer</a>)</p>
Journaling Support Group <br/>
All diagnoses <br/>
Every Monday, 2:30-4:00 PM <br/>
UNM Cancer Center
</li>
<li><p class='listItemHeader'>Taos Support Groups * </p>
Survivors (all cancers) Tuesdays, <br/>
5:00-6:30PM <br/>
Caregivers (all cancers) Mondays, <br/>
5:00-6:30PM <br/>
Sipapu St, Taos
</li-->
<!--li><p class='listItemHeader'>Thyroid Cancer Group * </p>
2nd Tuesday of the month, 6:30PM <br/>
On Fourth St. NW
</li>
<li><p class='listItemHeader'>UNM /CSN Education & Support Group (view <a href='#' onclick='window.open("pdfs/UNMEDSGflyer.pdf");'>flyer</a>)</p>
Survivors and/or Caregivers<br/>
1st and 3rd Monday, 5:30-7:00PM <br/>
Central United Methodist Church, University Blvd.
</li>
<li><p class='listItemHeader'>U27 (Under 27 years old)* </p>
Survivors, all diagnoses, male and female <br/>
3rd Wednesday <br/>
5:30-7:00PM <br/>
Location TBA
</li>
<li><p class='listItemHeader'>Valencia County Groups</p>
These are newly affiliated groups with CSN. Lisa Parson's groups provide Christian based cancer support.
<br/><br/>
Women&#39;s Group (survivors) "Ashes to Beauty"<br/>
Second Saturday of the month, 10:00AM-1:00PM<br/>
Milton Loop, Los Lunas<br/><br/>

Open Group (men &amp; women, survivors &amp; caregivers) <br/>
Every other Wednesday, 10:00AM-Noon (coffee available) Bosque Farms<br/><br/>
Facilitator: Lisa Parson (lparson21@gmail.com) <br/>
Call the CSN Helpline for more information: (505) 255-0405; toll free: (855) 955-3500
</li>
<li><p class='listItemHeader'>Gynecological Cancer Awareness Project</p>
Our newest affiliated support group, G-CAP has a mission to empower women who are fighting <br/>
gynecological and breast cancer by providing education and support - helping them to live a
healthy and inspired life.<br/>
<br/>
Email: cleversenoras@gmail.com <br/>
Web: https://www.thegcap.org/home-1.html <br/>
<br/>
7007 Wyoming NE Suite D3 <br/>
Albuquerque, NM 87109 <br/>
(505) 610-9300 <br/><br/>

They sponsor the Can Do Project, have their own
Library, Offer Massage Services at their office.
</li>
<li><p class='listItemHeader'>Prostate Cancer Support Association of New Mexico </p>
It is an affiliated support group with CSN. <br/>
Support group meetings are held 1st and 3rd Saturdays of the month, <br/>
meetings at Bear Canyon Senior Center.<br/>
Office is at 2533 Virginia St, NE Suite C Albuquerque, NM 87110 <br/>
Website: <a href='http://www.pcsanm.org' target='_blank'>www.pcsanm.org</a><br/>
Phone: (505)254-7784 <br/>
Email: pchelp@pcsanm.org<br/>
<br/>
<b>The Cancer Support Now Library is hosted here.</b>
</li-->
