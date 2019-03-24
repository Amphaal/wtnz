class BBEditor {
    constructor(targetId) {
        this._editor = document.getElementById(targetId);
        if(!this._editor) return;
        
        this._simblings = getSiblings(this._editor);
        this._picker = this._editor.getElementsByClassName("colorPicker")[0];
        this._validateBtn = this._editor.getElementsByClassName("validate")[0];
        this._validateBtn.addEventListener("click", this.applyColors.bind(this));
        this._cancelBtn = this._editor.getElementsByClassName("cancel")[0];
        this._cancelBtn.addEventListener("click", this.closePicker.bind(this));

        this._clickHandler = null;
        this._addClickHandler();
    }

    _addClickHandler() {
        if(!this._clickHandler) {
            this._clickHandler = this.openPicker.bind(this);
        }
        this._editor.addEventListener("click", this._clickHandler);
    }

    _removeClickHandler() {
        this._editor.removeEventListener("click", this._clickHandler);
    }

    applyColors() {
        this.closePicker();
    }

    _waitForSimblingsToTransition(func) {
        return this._simblings.map(function(e) {
            return waitTransitionEnd(e, func);
        });
    }

    openPicker() {

        this._removeClickHandler();

        let waitAll = this._waitForSimblingsToTransition(function(simbling) {
            simbling.style.pointerEvents = "none";
            simbling.style.opacity = 0;
        });

        Promise.all(waitAll).then(function() {
            this._editor.classList.add("open");
        }.bind(this));
    }

    closePicker() {

        waitTransitionEnd(this._picker, function() {
            this._editor.classList.remove("open");
        }.bind(this)).then(function() {

            let waitAll = this._waitForSimblingsToTransition(function(simbling) {
                simbling.style.pointerEvents = "";
                simbling.style.opacity = "";
            });

            Promise.all(waitAll).then(function() {

                this._addClickHandler();
            
            }.bind(this));

        }.bind(this));
    }
}