<form id="chat-form">
    @csrf
    <div class="form-group">
        <input type="text" name="message" id="message" class="form-control" placeholder="Enter Message">
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
</form>

<script src="{{ asset('js/app.js') }}" defer></script>
