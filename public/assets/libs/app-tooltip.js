class AppTooltip {
    constructor() {
        this.searchElement = '.AppTooltip[title]';
        this.dataTooltip = 'data-app-tooltip';
        this.tooltipMargin = 5; // Отступ от блока
        this.windowPadding = 10; // Отступ от краёв экрана
        this.tooltip = null;
        this.observer = null;
        this.init();
    }

    init() {
        this.addStyles();
        this.createTooltip();
        this.updateElements();
        this.bindEvents();
        this.setupMutationObserver();
    }

    addStyles() {
        $('head').append(`<style>
            #AppTooltip {
                position: absolute;
                max-width: 300px;
                padding: 6px 10px;
                background: #333;
                color: #fff;
                border-radius: 4px;
                font-size: 16px;
                line-height: 1.4;
                z-index: 9999;
                box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.2s ease;
                text-align: center;
            }
            #AppTooltip.show { opacity: 1; }
            #AppTooltip .AppTooltipArrow {
                position: absolute;
                top: -3px;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 0;
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-bottom: 5px solid #333;
            }
        </style>`);
    }

    createTooltip() {
        $('body').append('<div id="AppTooltip"><span></span><div class="AppTooltipArrow"></div></div>');
        this.tooltip = $('#AppTooltip');
    }

    updateElements() {
        $(this.searchElement).each((_, el) => {
            const $el = $(el);
            if (!$el.attr(this.dataTooltip)) {
                $el.attr(this.dataTooltip, $el.attr('title'));
                $el.removeAttr('title');
            }
        });
    }

    bindEvents() {
        // Делегирование событий на весь документ
        $(document)
            .on('mouseenter', `[${this.dataTooltip}]`, this.showTooltip.bind(this))
            .on('mouseleave', `[${this.dataTooltip}]`, this.hideTooltip.bind(this));
        
        $(window).on('scroll', this.hideTooltip.bind(this));
    }

    showTooltip(e) {
        const text = $(e.currentTarget).attr(this.dataTooltip);
        this.tooltip.find('span').html(text);
        
        // Позиция элемента на странице 
        var element = {
            offset: $(e.currentTarget).offset(),
            size: {
                width: $(e.currentTarget).outerWidth(),
                height: $(e.currentTarget).outerHeight(),
            }
        }
        var tooltip = {
            size: {
                width: this.tooltip.outerWidth(),
                height: this.tooltip.outerHeight(),
            },
            offset: {
                min: {
                    left: 0,
                    top: 0,
                },
                max: {
                    left: 0,
                    top: 0,
                },
                left: 0,
                top: 0
            }
        }
        tooltip.offset.min.left = $(document).scrollLeft() + this.windowPadding;
        tooltip.offset.max.left = $(window).width() + $(document).scrollLeft() - tooltip.size.width - this.windowPadding;
        tooltip.offset.max.top = $(window).scrollTop() + $(window).height();

        tooltip.offset.left = element.offset.left + element.size.width - element.size.width / 2 - tooltip.size.width / 2;
        tooltip.offset.top = element.offset.top + element.size.height + this.tooltipMargin;
        
        var shift = tooltip.offset.left;

        tooltip.offset.left = Math.max(tooltip.offset.left, tooltip.offset.min.left);
        tooltip.offset.left = Math.min(tooltip.offset.left, tooltip.offset.max.left);

        shift = shift - tooltip.offset.left;
        if(shift < 0) {
            shift = Math.max(tooltip.size.width / 2 * -1 + this.tooltipMargin * 2, shift);
        }
        if(shift > 0) {
            shift = Math.min(tooltip.size.width / 2 - this.tooltipMargin * 2, shift);
        }
        
        // // Если тултип выходит за нижнюю границу экрана, показываем его сверху
        if (tooltip.offset.top + tooltip.size.height > tooltip.offset.max.top) {
            tooltip.offset.top = element.offset.top - tooltip.size.height - this.tooltipMargin;
            this.tooltip.find('.AppTooltipArrow')
                .css({
                    'top': 'auto',
                    'bottom': '-4px',
                    'border-bottom': 'none',
                    'border-top': '5px solid #333',
                    'left': 'calc(50% + ' + shift + 'px)'
                });
        } else {
            this.tooltip.find('.AppTooltipArrow')
                .css({
                    'top': '-4px',
                    'bottom': 'auto',
                    'border-top': 'none',
                    'border-bottom': '5px solid #333',
                    'left': 'calc(50% + ' + shift + 'px)'
                });
        }
        
        this.tooltip.css({
            left: tooltip.offset.left + 'px',
            top: tooltip.offset.top + 'px'
        });
        this.tooltip.addClass('show');
    }

    hideTooltip() {
        this.tooltip.removeClass('show');
    }

    setupMutationObserver() {
        this.observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    this.updateElements();
                }
            });
        });

        // Наблюдаем за всем документом
        this.observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
        $(document).off('mouseenter mouseleave', `[${this.dataTooltip}]`);
        $(window).off('scroll');
        this.tooltip.remove();
        $(`[${this.dataTooltip}]`).each((_, el) => {
            const $el = $(el);
            $el.attr('title', $el.attr(this.dataTooltip));
            $el.removeAttr(this.dataTooltip);
        });
    }
}
$(document).ready(() => new AppTooltip());