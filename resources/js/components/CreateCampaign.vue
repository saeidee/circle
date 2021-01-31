<template>
    <div>
        <div class="w-100">
            <div class="d-flex align-items-baseline">
                <h4 class="mb-0 font-weight-bold">Create Campaign</h4>
            </div>
        </div>
        <div class="my-5 rounded-items base-shadow">
            <div class="w-2/4">
                <b-form>
                    <h6>Campaign Detail</h6>
                    <b-form-input
                        :value="name"
                        class="mb-2 mr-sm-2"
                        placeholder="Name"
                        @change="name => $emit('setName', name)"
                    />
                    <span
                        v-if="isInvalid('name', 'required')"
                        class="font-italic text-red-600 text-sm d-block">
                        Campaign name is required
                    </span>
                    <hr/>
                    <h6>Subject</h6>
                    <b-form-input
                        :value="subject"
                        class="mb-2 mr-sm-2"
                        placeholder=".........."
                        @change="subject => $emit('setSubject', subject)"
                    />
                    <span
                        v-if="isInvalid('subject', 'required')"
                        class="font-italic text-red-600 text-sm d-block">
                        Subject is required
                    </span>
                    <hr/>
                    <div class="mt-3">
                        <h6>From</h6>
                        <div class="d-flex justify-content-sm-between">
                            <div class="w-100 mr-sm-2">
                                <b-form-input
                                    :value="from.name"
                                    class="mb-2"
                                    placeholder="Name"
                                    @change="name => $emit('setFrom', { ...from, name })"
                                />
                                <span
                                    v-if="isInvalid('name', 'required', 'from')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    From name is required
                                </span>
                            </div>
                            <div class="w-100">
                                <b-form-input
                                    :value="from.email"
                                    class="mb-2"
                                    placeholder="Email"
                                    @change="email => $emit('setFrom', { ...from, email })"
                                />
                                <span
                                    v-if="isInvalid('email', 'required', 'from')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    From email is required
                                </span>
                                <span
                                    v-if="isInvalid('email', 'email', 'from')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    From email is incorrect
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="mt-3">
                        <h6>Reply To</h6>
                        <div class="d-flex justify-content-sm-between">
                            <div class="w-100 mr-sm-2">
                                <b-form-input
                                    :value="replyTo.name"
                                    class="mb-2"
                                    placeholder="Name"
                                    @change="name => $emit('setReplyTo', { ...replyTo, name })"
                                />
                                <span
                                    v-if="isInvalid('name', 'required', 'replyTo')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    Reply to name is required
                                </span>
                            </div>
                            <div class="w-100">
                                <b-form-input
                                    :value="replyTo.email"
                                    class="mb-2"
                                    placeholder="Email"
                                    @change="email => $emit('setReplyTo', { ...replyTo, email })"
                                />
                                <span
                                    v-if="isInvalid('email', 'required', 'replyTo')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    Reply to email is required
                                </span>
                                <span
                                    v-if="isInvalid('email', 'email', 'replyTo')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    Reply to email is incorrect
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="mt-3 mb-3">
                        <h6>Recipients</h6>
                        <div v-for="(recipient, index) in to" class="d-flex justify-content-sm-between">
                            <div
                                :class="{'mb-2': index === 0}"
                                class="w-100 mr-sm-2">
                                <b-form-input
                                    :ref="'recipient-name-' + index"
                                    :value="recipient.name"
                                    placeholder="Name"
                                    @change="name => setRecipientName(index, name)"
                                />
                                <span
                                    v-if="isInvalidRecipient(index, 'name', 'required')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    Recipient name is required
                                </span>
                            </div>
                            <div
                                :class="[index === 0 ? 'mr-sm-5 mb-2' : 'mr-sm-2']"
                                class="w-100">
                                <b-form-input
                                    :value="recipient.email"
                                    placeholder="Email"
                                    @change="email => setRecipientEmail(index, email)"
                                    @keypress.enter="addRecipient(index + 1)"
                                />
                                <span
                                    v-if="isInvalidRecipient(index, 'email', 'required')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    Recipient email is required
                                </span>
                                <span
                                    v-if="isInvalidRecipient(index, 'email', 'email')"
                                    class="font-italic text-red-600 text-sm d-block">
                                    Recipient email is incorrect
                                </span>
                            </div>
                            <b-button
                                v-if="index !== 0"
                                class="mb-2 h-50"
                                variant="danger"
                                @click="removeRecipient(index)">
                                <b-icon icon="x-circle"/>
                            </b-button>
                        </div>
                        <b-button
                            class="text-white mt-2"
                            variant="success"
                            @click="addRecipient(to.length - 1)">
                            <b-icon icon="plus"/>
                        </b-button>
                    </div>
                    <hr/>
                    <div class="mt-3 mb-3">
                        <h6>Content</h6>

                        <b-form-group v-slot="{ ariaDescribedby }">
                            <b-form-radio-group
                                id="content-type"
                                name="content-type"
                                :value="content.type"
                                :checked="content.type"
                                :options="contentTypeOptions"
                                :aria-describedby="ariaDescribedby"
                                @change="type => $emit('setContent', {...content, type})"
                            ></b-form-radio-group>
                        </b-form-group>
                        <b-form-textarea
                            v-if="isContentTextType"
                            id="content-text"
                            :value="content.value"
                            rows="6"
                            placeholder="Email content here..."
                            @input="value => $emit('setContent', {...content, value})"
                        ></b-form-textarea>
                        <codemirror
                            v-else
                            ref="cmEditor"
                            :value="content.value"
                            :options="codeMirrorOptions"
                            @input="value => $emit('setContent', {...content, value})"
                        />
                        <span
                            v-if="isInvalid('value', 'required', 'content')"
                            class="font-italic text-red-600 text-sm d-block">
                            Email should have a body
                        </span>
                    </div>
                    <b-button
                        variant="primary"
                        @click="create"
                    >
                        Create
                    </b-button>
                </b-form>
            </div>
        </div>
    </div>
</template>

<script>
    import { required, email } from 'vuelidate/lib/validators'
    import CampaignEnums from '../enums/CampaignEnums';
    import { codemirror } from 'vue-codemirror'
    import 'codemirror/lib/codemirror.css'

    import 'codemirror/mode/css/css.js'
    import 'codemirror/mode/xml/xml.js'
    import 'codemirror/mode/htmlmixed/htmlmixed.js'
    import 'codemirror/theme/base16-light.css'

    export default {
        name: 'CreateCampaign',

        components: {
            codemirror
        },

        props: {
            /**
             * @property {string} name
             */
            name: {
                type: String,
                required: true
            },

            /**
             * @property {string} subject
             */
            subject: {
                type: String,
                required: true
            },

            /**
             * @property {object} from
             */
            from: {
                type: Object,
                required: true
            },

            /**
             * @property {array} to
             */
            to: {
                type: Array,
                required: true
            },

            /**
             * @property {object} replyTo
             */
            replyTo: {
                type: Object,
                required: true
            },

            /**
             * @property {object} content
             */
            content: {
                type: Object,
                required: true
            }
        },

        computed: {
            /**
             * @return {boolean}
             */
            isContentTextType () {
                return this.content.type === CampaignEnums.TEXT_TYPE;
            }
        },

        data () {
            return {
                newRecipient: {
                    name: '',
                    email: ''
                },
                contentTypeOptions: [
                    { text: 'Text', value: CampaignEnums.TEXT_TYPE },
                    { text: 'HTML', value: CampaignEnums.HTML_TYPE }
                ],
                codeMirrorOptions: {
                    tabSize: 4,
                    mode: CampaignEnums.HTML_TYPE,
                    theme: 'base16-light',
                    lineNumbers: true,
                    line: true,
                    autofocus: true
                }
            };
        },

        methods: {
            /**
             * @param {number} index
             * @param {string} name
             * @return {void}
             */
            setRecipientName (index, name) {
                let recipients = [...this.to];
                recipients[index] = { ...recipients[index], name };

                this.$emit('setTo', recipients);
            },

            /**
             * @param {number} index
             * @param {string} email
             * @return {void}
             */
            setRecipientEmail (index, email) {
                let recipients = [...this.to];
                recipients[index] = { ...recipients[index], email };

                this.$emit('setTo', recipients);
            },

            /**
             * @param {number} index
             * @return {void}
             */
            addRecipient (index) {
                this.$emit('setTo', [...this.to, this.newRecipient]);

                this.$nextTick(() => {
                    const [component] = this.$refs[`recipient-name-${index}`];
                    component.$el.focus();
                });
            },

            /**
             * @param {number} recipientIndex
             * @return {void}
             */
            removeRecipient (recipientIndex) {
                const recipients = this.to.filter((recipient, index) => index !== recipientIndex);

                this.$emit('setTo', recipients);
            },

            /**
             * @param {string} input
             * @param {string} rule
             * @param {string|null} parent
             * @return {boolean}
             */
            isInvalid (input, rule, parent = null) {
                if (parent) {
                    return this.$v[parent][input].$error && !this.$v[parent][input][rule]
                }

                return this.$v[input].$error && !this.$v[input][rule]
            },

            /**
             * @param {number} index
             * @param {string} input
             * @param {string} rule
             * @return {boolean}
             */
            isInvalidRecipient (index, input, rule) {
                return this.$v.to.$each.$iter[index][input].$error
                    && !this.$v.to.$each.$iter[index][input][rule];
            },

            /**
             * @return {void}
             */
            create () {
                if (this.$v.$touch() || this.$v.$error) {
                    this.$bvToast.toast(
                        'Please fill the required fields',
                        {
                            title: 'Warning',
                            variant: 'danger',
                            solid: true
                        }
                    );

                    return;
                }

                this.$v.$reset();
                this.$emit('create');
            }
        },

        validations: {
            name: {
                required,
            },
            subject: {
                required
            },
            from: {
                name: {
                    required
                },
                email: {
                    required,
                    email
                }
            },
            replyTo: {
                name: {
                    required
                },
                email: {
                    required,
                    email
                }
            },
            to: {
                $each: {
                    name: {
                        required
                    },
                    email: {
                        required,
                        email
                    }
                }
            },
            content: {
                type: {
                    required
                },
                value: {
                    required
                }
            }
        }
    }
</script>
