	<div class="row">

		<div class="col-xl-3">
			<label>Filter Group</label>
				<select id="filter_groups" class="form-control form-control-lg">
						<option value="">Filter By Group</option>
					@foreach ($groups as $key => $group)
						<option value="{{$group->id}}">{{$group->name}}</option>
					 @endforeach
				</select>						
		</div>

		<div class="col-xl-3">
			<label>Filter Collector</label>
				<select id="filter_collector" class="form-control form-control-lg">
					<option value="0">Select Collector</option>
				</select>					
		</div>

		<div class="col-xl-3">
			<label>Filter Status</label>
				<select id="filter_status" class="form-control form-control-lg">
					<option value="">All Status</option>
				</select>					
		</div>

		<div class="col-xl-3">
			<label>Action</label>
			<button type="button" id="btn_pullout_all_accounts" class="btn btn-danger btn-block" disabled="true">
				<i class="fas fa-folder-open"></i> Pullout All the Accounts
			</button>					
		</div>	
		 

								

	</div>
	<hr>
	<div class="row">

		<div class="col-xl-3">
			<button type="button" id="btn_pullout_accounts" class="btn btn-secondary btn-block" disabled="true">
				<i class="fas fa-sign-out-alt"></i> Pullout Selected Account
			</button>					
		</div>	

					

	</div>
	<hr>
	<table id="tbl_leads" class="table table-bordered table-hover table-checkable">
		<thead>
			<tr>
				<th>Record ID</th>
				<th>Account Status</th>
				<th>Collector</th>
				<th>Account Number</th>
				<th>Name</th>
				<th>Group</th>
				<th>Created Date</th>
			</tr>
		</thead>
	</table>
