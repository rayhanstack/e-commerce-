@props(['inputs' => 6])

<div class="otp-input-container d-flex gap-2 justify-content-center" x-data="{
    length: {{ $inputs }},
    focusNext(index, event) {
        const input = event.target;
        if (input.value.length === 1 && index < this.length) {
            document.getElementById('otp_' + (index + 1)).focus();
        }
    },
    handleBackspace(index, event) {
        if (event.key === 'Backspace' && event.target.value === '' && index > 1) {
            document.getElementById('otp_' + (index - 1)).focus();
        }
    }
}">
    @for ($i = 1; $i <= $inputs; $i++)
        <input 
            type="text" 
            id="otp_{{ $i }}" 
            name="otp[]" 
            maxlength="1" 
            class="form-control text-center form-control-lg" 
            style="width: 3rem; height: 3.5rem; font-size: 1.5rem;"
            @input="focusNext({{ $i }}, $event)"
            @keydown="handleBackspace({{ $i }}, $event)"
            {{ $attributes }}
        >
    @endfor
</div>
