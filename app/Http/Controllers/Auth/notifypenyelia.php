<?php
    
    require 'JPhpMailer.php';
    $db_connection = pg_connect("host=jkrdbp.jkr.gov.my dbname=sumber user=sepakat password=$3p4k4t$");

        $getyear = date("Y");
        $query = pg_query($db_connection, "Select * from cidb.ansstatus where status=1 and year='$getyear' and approvename!='' and timestampsuper is NULL");
        while($row = pg_fetch_array($query)) 
        {
        //penyelia email
        $penyeliaIC = $row['approvedby'];
        $penyeliadetails_get = pg_query($db_connection, "Select * from peribadi where nokp='$penyeliaIC'");
        while($penyeliadetails = pg_fetch_array($penyeliadetails_get)){
            $emailuserbetul = $penyeliadetails['email'];
        }
        //penjawab details
        $useric = $row['nric'];
        $penjawabdetails_get = pg_query($db_connection, "select * from peribadi where nokp='$useric'");
        while($penjawabdetails = pg_fetch_array($penjawabdetails_get)){
            $namauser = $penjawabdetails['nama'];
            $nokpuser = $penjawabdetails['nokp'];
            $emailuserUser = $penjawabdetails['email'];
        }
        //penjawab jawatan
        $jawatanuser_get = pg_query($db_connection, "select * from perkhidmatan where nokp='$useric' and flag=1");
        while($jawatanuser = pg_fetch_array($jawatanuser_get)){
            $jawatanuser_kod = $jawatanuser['kod_jawatan'];
        }
        $greduser = $row['gred'];
        //user job group
        $row_kodjawatan = $row['jgcode'];
        $jobgroupuser_get = pg_query($db_connection, "select * from cidb.jobgroup where jgcode='$row_kodjawatan'");
        while($jobgroupuser = pg_fetch_array($jobgroupuser_get)){
            $jgcode = $jobgroupuser['jgcode'];
            $jobgroup_name = $jobgroupuser['jobgroup_en'];

        }
        //user jawatan
        $get_jawatan = pg_query($db_connection, "select * from l_jawatan where kod_jawatan='$jawatanuser_kod'");
        while($getjawatan = pg_fetch_array($get_jawatan)){
            $thejawatan = $getjawatan['jawatan'];
        }
        //user kod waran pejabat
        $kod_pej = $row['kod_waran_pej'];
        $getpej_get = pg_query($db_connection, "select * from l_waran_pej where kod_waran_pej='$kod_pej'"); 
        while($getpej = pg_fetch_array($getpej_get)){
            $thegetpej = $getpej['waran_pej'];
        }

        $date=date_create($row['timestampsubmit']);
        $dateofsend = date_format($date,"Y-m-d");
        $date1=date_create($dateofsend);
        $date2=date_create(date("Y-m-d"));
        $diff=date_diff($date1,$date2);
        $differencedays =  $diff->format("%a");

       
        
         
         if($differencedays == 5){
         $headers = "From: NO REPLY CI\r\nReply-To: '.$emailuserbetul.'";
         $emailtext = '<html>
 <head>
 </head>
 <body>
 Tuan/Puan,
 <br>
 <br>
 <u><b style="font-size:25px;">PENGESAHAN PENILAIAN KOMPETENSI PEGAWAI</b></u>
<br>
<br>
Adalah dimaklumkan penilaian kompetensi pegawai berikut telah dihantar untuk pengesahan tuan/puan:
<br>
<br>
<table>
<col width="250">
  <tr>
    <td><b>NAMA:</b> </td>
    <td>'.$namauser.'</td>
  </tr>
  <tr>
     <td><b>IC:</b></td>
     <td>'.$nokpuser.'</td>
   </tr>
   <tr>
     <td><b>JAWATAN:</b></td>
     <td>'.$thejawatan.', GRED '.$greduser.'</td>
   </tr>
   <tr> 
     <td><b>Job Group:</b></td>
     <td>'.$jgcode.' - '.$jobgroup_name.'</td>
   </tr>
   <tr>
     <td><b>UNIT/BAHAGIAN/CAWANGAN:&nbsp;</b></td>
     <td>'.$thegetpej.'</td>
   </tr>
 </table>
 <br>
 <br>
 <br>
 Sekian, terima kasih.
 <br>
 <br>
 <br>
 <div style="font-size:12px;font-style:italic">
 //Adalah dimaklumkan bahawal emel ini dijana secara auto melalui Aplikasi Competency Identification JKR.<br>
 Sila abaikan email ini sekiranya tindakan telah diambil.
 </div>
 </body>
 </html>';
 
         $to = $emailuserbetul;
         $mail = new JPhpMailer;
         $mail->IsSMTP();
         $mail->Host = 'postmaster.1govuc.gov.my';
         $mail->Port = 25;
         $mail->SMTPAuth = false;
         $mail->Username = '';
         $mail->Password = '';
         $mail->SetFrom($emailuserUser, 'No Reply');
         $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
         $mail->Subject = 'Peringatan Penyemakan CI';
         $msg = $emailtext;
         $mail->MsgHTML($msg);
         $mail->AddAddress($to);
         $mail->Send();
         if(!$mail->Send())
{
   echo "Message was not sent";
   echo "Mailer Error: " . $mail->ErrorInfo;
} else {
   echo "Message has been sent";
}
        }
       }