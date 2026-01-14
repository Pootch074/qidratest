<form method="POST" action="{{ route('otp.verify.submit') }}">
    @csrf
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <p class="text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>OTP Code</label>
        <input type="text" name="otp_code" required>
        @error('otp_code')
            <p class="text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit">Verify</button>
</form>
