import {Controller} from "@hotwired/stimulus";
import 'ckeditor5/ckeditor5.css';
import {
    ClassicEditor,
    AccessibilityHelp,
    Alignment,
    Autoformat,
    AutoImage,
    AutoLink,
    Autosave,
    BalloonToolbar,
    BlockQuote,
    Bold,
    CloudServices,
    Code,
    FindAndReplace,
    FontSize,
    Heading,
    HorizontalLine,
    ImageBlock,
    ImageCaption,
    ImageInline,
    ImageInsertViaUrl,
    ImageResize,
    ImageStyle,
    ImageTextAlternative,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    ListProperties,
    Paragraph,
    RemoveFormat,
    SelectAll,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Strikethrough,
    Subscript,
    Superscript,
    Table,
    TableCellProperties,
    TableColumnResize,
    TableProperties,
    TableToolbar,
    TextTransformation,
    Underline,
    Undo,
    Editor,
    MediaEmbed,
    PasteFromMarkdownExperimental,
    PasteFromOffice,
    EditorConfig
} from 'ckeditor5';
import en from 'ckeditor5/translations/en.js';
import FullScreen from "app/vendor/ckeditor5/full-screen-plugin";
import {useDebounce} from "stimulus-use";
import NotImplementedException from "app/errors/not-implemented-exception";

enum Mode
{
    Standard = 'standard',
    Simple = 'simple',
}

export default class TextEditorController extends Controller
{
    declare private textareaTarget: HTMLTextAreaElement;
    declare private modeValue: Mode;

    private editor: Editor | null = null;

    static targets = [
        'textarea',
    ];
    static values = {
        mode: {type: String, default: Mode.Standard},
    }
    static debounces = [
        'updateTextarea',
    ]

    public async connect() {
        useDebounce(this, {wait: 500});
        let config: EditorConfig;
        if (this.isModeStandard()) {
            config = this.getConfigForStandardMode();
        } else if (this.isModeSimple()) {
            config = this.getConfigForSimpleMode();
        } else {
            throw new NotImplementedException();
        }
        this.editor = await ClassicEditor.create(this.textareaTarget, config);
        this.editor.model.document.on('change:data', () => this.updateTextarea());
    }

    private updateTextarea(): void {
        this.textareaTarget.value = this.editor?.getData() ?? '';
        this.textareaTarget.dispatchEvent(new Event('change', {bubbles: true, cancelable: true}));
    }

    private getConfigForStandardMode(): EditorConfig {
        const config = this.getBaseConfig();
        config.toolbar = {
            items: [
                'fullScreen',
                '|',
                'undo',
                'redo',
                '|',
                'heading',
                '|',
                'fontSize',
                '|',
                'bold',
                'italic',
                'underline',
                '|',
                'link',
                'insertTable',
                'blockQuote',
                '|',
                'alignment',
                '|',
                'bulletedList',
                'numberedList',
                'outdent',
                'indent'
            ],
            shouldNotGroupWhenFull: false
        };
        return config;
    }

    private getConfigForSimpleMode(): EditorConfig {
        const config = this.getBaseConfig();
        config.toolbar = {
            items: [
                'fullScreen',
                '|',
                'undo',
                'redo',
                '|',
                'bold',
                'italic',
                'underline',
                '|',
                'link',
                'blockQuote',
                '|',
                'alignment',
                '|',
                'bulletedList',
                'numberedList',
                'outdent',
                'indent'
            ],
            shouldNotGroupWhenFull: false
        };
        config.menuBar = {
            isVisible: false,
        };
        return config;
    }

    private getBaseConfig(): EditorConfig {
        return {
            plugins: [
                AccessibilityHelp,
                Alignment,
                Autoformat,
                AutoImage,
                AutoLink,
                Autosave,
                BalloonToolbar,
                BlockQuote,
                Bold,
                CloudServices,
                Code,
                FindAndReplace,
                FontSize,
                Heading,
                HorizontalLine,
                ImageBlock,
                ImageCaption,
                ImageInline,
                ImageInsertViaUrl,
                ImageResize,
                ImageStyle,
                ImageTextAlternative,
                ImageToolbar,
                ImageUpload,
                Indent,
                IndentBlock,
                Italic,
                Link,
                LinkImage,
                List,
                ListProperties,
                Paragraph,
                RemoveFormat,
                SelectAll,
                SpecialCharacters,
                SpecialCharactersArrows,
                SpecialCharactersCurrency,
                SpecialCharactersEssentials,
                SpecialCharactersLatin,
                SpecialCharactersMathematical,
                SpecialCharactersText,
                Strikethrough,
                Subscript,
                Superscript,
                Table,
                TableCellProperties,
                TableColumnResize,
                TableProperties,
                TableToolbar,
                TextTransformation,
                Underline,
                Undo,
                MediaEmbed,
                PasteFromMarkdownExperimental,
                PasteFromOffice,
                FullScreen,
            ],
            balloonToolbar: ['bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList'],
            heading: {
                options: [
                    {
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph',
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 1',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 2',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 3',
                        class: 'ck-heading_heading5'
                    },
                ]
            },
            image: {
                toolbar: [
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'imageStyle:inline',
                    'imageStyle:wrapText',
                    'imageStyle:breakText',
                    '|',
                    'resizeImage'
                ]
            },
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                decorators: {
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            menuBar: {
                isVisible: true
            },
            placeholder: this.element.getAttribute('placeholder')?.toString() ?? '',
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
            },
            language: {
                ui: APP_CONFIG.common.locale,
            },
            translations: [
                en,
            ],
            updateSourceElementOnDestroy: true,
        }
    }

    private isModeSimple(): boolean {
        return this.modeValue === Mode.Simple;
    }

    private isModeStandard(): boolean {
        return this.modeValue === Mode.Standard;
    }

    public async disconnect() {
        if (this.editor !== null) {
            await this.editor.destroy();
        }
    }
}
