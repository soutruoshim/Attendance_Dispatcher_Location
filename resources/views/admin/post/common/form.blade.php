

<div class="row">
    
    <div class="col-lg-6 mb-3">
        <label for="branch_id" class="form-label">Branch <span style="color: red">*</span></label>
        <select class="form-select" id="branch" name="branch_id" required >
            <option value="" {{!isset($userDetail) || old('branch_id') ? 'selected': ''}}  disabled >Select Branch</option>
            @if(isset($companyDetail))
                @foreach($companyDetail->branches()->get() as $key => $branch)
                    <option value="{{$branch->id}}"
                        {{ isset($postDetail) && ($postDetail->branch_id ) == $branch->id || old('branch_id') == $branch->id ? 'selected': '' }}>
                        {{ucfirst($branch->name)}}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="department" class="form-label">Departments <span style="color: red">*</span></label>
        <select class="form-select" id="department" name="dept_id" required>
               
        </select>
    </div>

    {{-- <div class="col-lg-4 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Department <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="dept_id" required>

            <option value=""  disabled >Select Department</option>
            @foreach($departmentDetail as $key => $department)
                <option value="{{ $department->id }}" {{ (isset($postDetail) && $department->id === $postDetail->dept_id )? 'selected':''}}>
                    {{ucfirst($department->dept_name)}}
                </option>
            @endforeach
        </select>
    </div> --}}

    {{-- <div class="col-lg-6 mb-3">
        <label for="name" class="form-label"> Post Name <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="post_name" required name="post_name" value="{{ ( isset($postDetail) ? $postDetail->post_name: '' )}}" autocomplete="off" placeholder="">
    </div> --}}

    <div class="col-lg-6 mb-3">
        <label for="department" class="form-label">Position <span style="color: red">*</span></label>
        <select class="form-control js-example-basic-single" id="post_name" name="post_name" required>
            <option value="" {{!isset($postDetail) ? 'selected': ''}} disabled>Filter Position</option>
        </select>
    </div>

    <div class="col-lg-6 mb-3" {{!isset($postDetail) ? 'hidden': ''}}>
        <label for="exampleFormControlSelect1" class="form-label">Status</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value=""  {{!isset($postDetail) ? 'selected': ''}} disabled>Select status</option>
            <option value="1" {{!isset($postDetail) ? 'selected': ''}} {{ isset($postDetail) && ($postDetail->is_active ) == 1 ? 'selected': old('is_active') }}>Active</option>
            <option value="0" {{ isset($postDetail) && ($postDetail->is_active ) == 0 ? 'selected': old('is_active') }}>Inactive</option>
        </select>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{isset($postDetail)? 'Update':'Create'}} Post</button>
    </div>
</div>
<style>
    .select2-container .select2-selection--single{
        box-sizing: border-box;
        cursor: pointer;
        height: 46px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 45px!important; 
        position: absolute;
        top: 1px;
        right: 1px;
        width: 20px;
    }
    </style>

