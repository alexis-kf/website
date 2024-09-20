import './stat.scss';
import StatTemplate from './stat.twig';

export default {
  title: 'Editorial/Stat',
  argTypes: {
    heading: {
      description: 'Stat heading',
      control: 'text',
    },
    headingModifier: {
      table: { disable: true }
    },
    body: {
      description: 'Stat text',
      control: 'text',
    },
    media: {
      name: 'Media type',
      description: 'Explore media options.',
      control: 'select',
      options: {
        'Icon': '<img src="/images/custom-icon.svg" class="img-fluid" alt="Placeholder" />',
        'Logo': '<img src="/images/logo-example.svg" class="img-fluid" alt="Placeholder" />',
        'Image': '<img src="https://via.placeholder.com/150x150.png" class="img-fluid rounded" alt="Placeholder" />',
      },
    },
    modifier: {
      description: 'Stat modifier',
      control: 'select',
      options: {
        'Default': 'text-dark',
        'Reverse': 'bg-dark text-light p-1',
      },
    }
  },
  decorators: [
    (story, args) => `<div style="max-width:400px;" class="${args.args.modifier} sb-only">${story()}</div>`,
  ],
};

export const Stat = StatTemplate.bind({});
Stat.args = {
  media: '<img src="/images/custom-icon.svg" class="img-fluid" alt="Placeholder" />',
  heading: 'This is small headling',
  headingModifier: 'stat-item_heading',
  body: 'Use our customizable design system as a foundation and expand from there to meet your organization’s needs.',
  modifier: 'text-dark',
};
