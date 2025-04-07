<!-- Edit Form Div (Initially Hidden) -->
<div id="edit-file" style="display:none;">
    <div class="card">
        <div class="card-header">
            <h5>Edit Member</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('members.update', ':id') }}" method="POST" id="editMemberForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="memberId" value="">

                <!-- Member Details Fields -->
                <div class="mb-3">
                    <label for="barangay" class="form-label">Barangay</label>
                    <input type="text" name="barangay" id="barangay" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="slp" class="form-label">SLP</label>
                    <input type="text" name="slp" id="slp" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="member" class="form-label">Member</label>
                    <input type="text" name="member" id="member" class="form-control">
                </div>
                <!-- Add other fields as needed -->

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Cancel</button>
            </form>
        </div>
    </div>
</div>
