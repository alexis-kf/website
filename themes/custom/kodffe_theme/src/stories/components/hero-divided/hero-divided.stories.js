import './hero-divided.scss';
import HeroDividedTemplate from './hero-divided.twig';

export default {
  title: 'Editorial/Hero Divided',
  argTypes: {
    eyebrow: {
      name: 'Eyebrow Text',
      description: 'Eyebrow displays above heading.',
      control: 'text'
    },
    title: {
      name: 'Heading',
      description: 'Main headline, usually the page title.',
      control: 'object'
    },
    subtitle: {
      name: 'Subhead',
      description: 'Subhead displays after heading.',
      control: 'object'
    },
    body_text: {
      name: 'Body Text',
      description: 'Body text displays after title and subtitle.',
      control: 'object'
    },
    button: {
      name: 'Button',
      description: 'Call to action link styled as button.',
      control: 'object'
    },
    modifier: {
      name: 'Modifier',
      description: 'Modify the text alignment and position.',
      control: 'select',
      options: {
        'Row > media first': 'flex-column flex-lg-row align-items-lg-center is-row sb-only',
        'Row > media last': 'flex-column flex-lg-row-reverse align-items-lg-center is-row sb-only',
        'Stacked > media first': 'flex-column is-stacked sb-only',
        'Stacked > media last': 'flex-column-reverse is-stacked sb-only'
      },
    },
    maxWidth: {
      name: 'Max Width',
      description: 'Controls component’s maximum width.',
      control: 'select',
      options: {
        'Default': '100%',
        'Max 900': '900px',
        'Max 1200': '1200px',
      },
    },
    subtitle_modifier: {
      table: { disable: true }
    },
    media: {
      table: { disable: true }
    }
  },
  decorators: [
    (story, args) => `<div style="max-width:${args.args.maxWidth || '100%' };position:relative;margin:0 auto;" class="sb-only">${story()}</div>`,
  ],
};

export const HeroDivided = HeroDividedTemplate.bind({});
HeroDivided.args = {
  media: '<img src="https://picsum.photos/1600/900" class="img-fluid rounded" alt="Placeholder image" />',
  eyebrow: 'Kodffe Eyebrow',
  title: 'Shortcut your design and development',
  subtitle: 'Quickly design and customize responsive mobile-first sites with Bootstrap.',
  body_text: '<p>Lorem ipsum dolor sit amet consectetur. Congue vel sagittis eu ullamcorper vel. Et et dui est ante tempor egestas pellentesque odio. Ornare erat lacus commodo porttitor ut enim. Ultricies mauris blandit in fermentum fringilla mollis risus ut. Nam eget eu suspendisse ut fermentum nascetur pretium lectus. Odio amet amet nam viverra hendrerit diam et. Nibh nunc in senectus odio tempor vitae id. Sit ut sit porta nisl enim.</p>',
  button: {
    text: 'Button label',
    url: '#',
    icon: 'arrow_right_alt'
  },
  modifier: 'flex-column flex-lg-row align-items-center is-row sb-only'
};
