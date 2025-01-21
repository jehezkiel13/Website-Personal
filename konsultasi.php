<?php
include "conf/inc.koneksi.php";
?>
<div class="panel panel-default">
  
  <div class="panel-heading">
              <h3 class="panel-title"><span class="glyphicon glyphicon-hand-up"></span> Konsultasi</h3>
            </div>
			
<div class="panel-body">
    <form class="form-horizontal" method="post" action="?page=processreg" >
		
		<div class="form-group">
                    <label class="col-sm-3 control-label">Nama</label>
                    <div class="col-sm-9">
					<input class="form-control"  type="text" name="nama" value="" placeholder="Isi Nama Lengkap" />
					</div>
		</div>
					
		<div class="form-group">
                    <label class="col-sm-3 control-label">Jenis Kelamin</label>
                    <div class="col-sm-9">
					<input  type="radio" name="jk" value="P"  /> Laki-laki 
					<input  type="radio" name="jk" value="W"  /> Wanita 					
					</div>
		</div>
		
		<div class="form-group">
                    <label class="col-sm-3 control-label">Alamat</label>
                    <div class="col-sm-9">
					<input class="form-control"  type="text" name="alamat" value=""  placeholder="Isi Alamat Lengkap"/>				
					</div>
		</div>
					
		<div class="form-group">
                    <label class="col-sm-3 control-label">Pekerjaan</label>
                    <div class="col-sm-9">
					<input class="form-control"  type="text" name="pekerjaan" value="" placeholder="Isi Pekerjaan Anda" />		
					</div>
		</div>
		
		<div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-9">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-hand-up"></span> Proses</button>
				<input type="reset" class="btn btn-danger" name="reset" value="Reset" />
                </div> 
		</div>				

	</form>

</div>


</div>
