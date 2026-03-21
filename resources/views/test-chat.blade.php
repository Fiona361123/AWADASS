<!DOCTYPE html>
<html>
<body>
    <h2>Start a chat with an employer</h2>

    <!-- List all employers -->
    @foreach(\App\Models\User::where('role', 'employer')->get() as $employer)
        <form method="POST" action="{{ route('chat.start') }}">
            @csrf
            <input type="hidden" name="employer_id" value="{{ $employer->id }}">
            <button type="submit">Chat with {{ $employer->name }}</button>
        </form>
    @endforeach
</body>
</html>