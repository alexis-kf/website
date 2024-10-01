import './button.scss';
import ButtonTemplate from './button.twig';

export default {
  title: 'General/Button',
  argTypes: {
    button: {
      name: 'Button object',
      description: 'Define the button content',
      control: 'object',
      type: {
        required: true,
      },
    },
    modifier: {
      name: 'Reverse',
      description: 'Reverse colors on dark background',
      control: 'boolean',
    }
  },
  decorators: [
    (story, args) => `<div class="sb-only${args.args.modifier ? ' bg-dark' : ''}">${story()}</div>`,
  ]
};

export const Primary = ButtonTemplate.bind({});
Primary.args = {
  button: {
    url: '#',
    text: 'Primary Button',
    icon: 'arrow_right_alt',
    modifier: 'btn-primary has-icon',
  },
  modifier: false,
};

export const Secondary = ButtonTemplate.bind({});
Secondary.args = {
  button: {
    url: '#',
    text: 'Secondary Button',
    icon: 'arrow_right_alt',
    modifier: 'btn-secondary has-icon',
  },
  modifier: false,
};

export const PrimaryOutlined = ButtonTemplate.bind({});
PrimaryOutlined.args = {
  button: {
    url: '#',
    text: 'Primary Button',
    icon: '',
    modifier: 'btn-outline-primary',
  },
  modifier: false,
};

export const SecondaryOutlined = ButtonTemplate.bind({});
SecondaryOutlined.args = {
  button: {
    url: '#',
    text: 'Secondary Button',
    icon: '',
    modifier: 'btn-outline-secondary',
  },
  modifier: false,
};

export const PrimarySmall = ButtonTemplate.bind({});
PrimarySmall.args = {
  button: {
    url: '#',
    text: 'Primary Button Small',
    icon: '',
    modifier: 'btn-primary btn-sm',
  },
  modifier: false,
};

export const SecondarySmall = ButtonTemplate.bind({});
SecondarySmall.args = {
  button: {
    url: '#',
    text: 'Secondary Button Small',
    icon: '',
    modifier: 'btn-secondary btn-sm',
  },
  modifier: false,
};
