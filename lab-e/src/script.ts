import "./style-buttons.css";

const msg: string = "Hello!";
alert(msg);

type styleInfo = {
    label: string;
    file: string;
}

type styleDict = {
    [key: string]: styleInfo;
}

class StyleManager {
    private currenStyle: string;
    private styles: styleDict;

    constructor() {
        this.styles = {
            "style-1": { label: "Styl pastelowy", file: "style-1.css" },
            "style-2": { label: "Styl ciemny",    file: "style-2.css" },
            "style-3": { label: "Styl trzeci",    file: "style-3.css" },
        };
        this.currenStyle = "style-1";
    }

    applyStyle(style: string): void {
        const oldStyle = document.getElementById("active-style");
        if (oldStyle)
            oldStyle.remove();

        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = this.styles[style].file;
        link.id = "active-style";
        document.head.appendChild(link);

        this.currenStyle = style;
    }

    createButtons(): void {
        const container = document.createElement("div");
        container.id = "style-button";

        for (const style in this.styles) {
            const btn = document.createElement("button");
            btn.textContent = this.styles[style].label;
            btn.className = "style-btn";
            btn.addEventListener("click", () => this.applyStyle(style));
            container.appendChild(btn);
        }
        document.body.appendChild(container);
    }

    init(): void {
        this.createButtons();
        this.applyStyle(this.currenStyle);
    }
}

const manager = new StyleManager();
manager.init();
