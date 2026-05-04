{{-- ── Dynamic Options Script ── --}}
<script>
    const optionsContainer   = document.getElementById('optionsContainer');
    const optionsSection     = document.getElementById('optionsSection');
    const optionsHeader      = document.getElementById('optionsHeader');
    const addBtnContainer    = document.getElementById('addOptionBtnContainer');
    let optionCount = 0;

    // Show the section as soon as a type is selected
    document.getElementById('type').addEventListener('change', () => {
        if (optionsSection) optionsSection.style.display = 'block';
    });


    function handleTypeChange() {
        const type = document.getElementById('type').value;
        optionsSection.style.display = 'block';
        optionsContainer.innerHTML   = '';
        addBtnContainer.innerHTML    = '';
        optionCount = 0;

        const titles = {
            binary           : 'Binary Options',
            single_choice    : 'Single Choice Options',
            multiple_choice  : 'Multiple Choice Options',
            number_input     : 'Correct Number Answer',
            text_input       : 'Correct Text Answer(s)',
        };
        optionsHeader.innerHTML = `<p class="section-title" style="margin-top:0;">${titles[type] || ''}</p>`;

        if (type === 'binary') {
            addOptionRow('True / Yes', 'text', true);
            addOptionRow('False / No', 'text', false);
        } else if (type === 'single_choice' || type === 'multiple_choice') {
            addOptionRow('', 'both', false);
            addOptionRow('', 'both', false);
            renderAddBtn(() => addOptionRow('', 'both', false), '+ Add Option');
        } else if (type === 'number_input') {
            addOptionRow('', 'number', true, true);
        } else if (type === 'text_input') {
            addOptionRow('', 'text', true, true);
            renderAddBtn(() => addOptionRow('', 'text', true, true), '+ Add Acceptable Answer');
        }
    }

    function renderAddBtn(action, label) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-default btn-sm';
        btn.style.marginTop = '0.5rem';
        btn.textContent = label;
        btn.onclick = action;
        addBtnContainer.appendChild(btn);
    }

    function addOptionRow(defaultText = '', mode = 'both', defaultCorrect = false, forceCorrect = false) {
        const type = document.getElementById('type').value;
        const idx  = optionCount++;
        const row  = document.createElement('div');
        row.className = 'option-builder-row';

        let html = '';

        // ── Correctness selector ──
        if (forceCorrect) {
            html += `<input type="hidden" name="options[${idx}][is_correct]" value="1">`;
        } else {
            const itype   = (type === 'single_choice' || type === 'binary') ? 'radio' : 'checkbox';
            const checked = defaultCorrect ? 'checked' : '';
            html += `
                <label title="Mark as correct" style="display:flex; align-items:center; cursor:pointer; flex-shrink:0;">
                    <input type="${itype}" name="options[${idx}][is_correct]" value="1" ${checked}
                           style="width:1.1rem; height:1.1rem; accent-color: var(--primary); cursor:pointer;">
                </label>`;
        }

        // ── Text / Number input ──
        html += `<div style="flex:1; display:flex; flex-direction:column; gap:0.5rem;">`;
        if (mode === 'text' || mode === 'both') {
            html += `<input type="text" name="options[${idx}][text_content]" class="form-control"
                         placeholder="Option text" value="${defaultText.replace(/"/g,'&quot;')}">`;
        } else if (mode === 'number') {
            html += `<input type="number" name="options[${idx}][text_content]" class="form-control"
                         placeholder="Enter exact number" step="any" value="${defaultText}" required>`;
        }
        if (mode === 'both') {
            html += `<input type="file" name="options[${idx}][image]" class="form-control"
                         accept="image/*" style="font-size:0.8rem;">`;
        }
        html += `</div>`;

        // ── Remove button (not for binary / first two rows) ──
        const removable = (type === 'single_choice' || type === 'multiple_choice' || type === 'text_input') && idx > 1;
        if (removable) {
            html += `<button type="button" class="btn btn-danger btn-sm" style="flex-shrink:0;"
                         onclick="this.closest('.option-builder-row').remove()">✕</button>`;
        }

        row.innerHTML = html;

        // Single‑answer radio exclusivity
        if (type === 'single_choice' || type === 'binary') {
            const radio = row.querySelector('input[type="radio"]');
            if (radio) {
                radio.addEventListener('change', () => {
                    optionsContainer.querySelectorAll('input[type="radio"]').forEach(r => {
                        if (r !== radio) r.checked = false;
                    });
                });
            }
        }

        optionsContainer.appendChild(row);
    }

    // ── Pre-populate existing options (edit mode) ──
    @if(!is_null($existingOptions) && !is_null($questionType))
        window.addEventListener('DOMContentLoaded', () => {
            const sel = document.getElementById('type');
            if (sel) sel.value = "{{ $questionType }}";
            handleTypeChange();

            // Clear any defaults and insert real data
            optionsContainer.innerHTML = '';
            optionCount = 0;

            const data = @json($existingOptions ?? []);
            data.forEach(opt => {
                const mode = (['number_input'].includes("{{ $questionType }}")) ? 'number'
                           : (['text_input', 'binary'].includes("{{ $questionType }}")) ? 'text'
                           : 'both';
                const force = ['number_input', 'text_input'].includes("{{ $questionType }}");
                addOptionRow(opt.text_content || '', mode, opt.is_correct == 1, force);
            });
        });
    @endif
</script>
