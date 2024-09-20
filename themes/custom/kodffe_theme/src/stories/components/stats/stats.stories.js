import './stats.scss';
import StatsTemplate from './stats.twig';

export default {
  title: 'Editorial/Stats',
  argTypes: {
    eyebrow: {
      name: 'Eyebrow Text',
      description: 'Eyebrow displays above heading.',
      control: 'text'
    },
    title: {
      name: 'Heading',
      description: 'Headline displayed for Stat Grid..',
      control: 'object'
    },
    media: {
      description: 'Stats image markup',
      table: { disable: true }
    },
    body: {
      description: 'Lead text displayed for Stat Grid.',
      control: 'text',
    },
    modifier: {
      description: 'Stats modifier',
      control: 'select',
      options: {
        'Default': 'dark bg-dark text-light',
        'Stat List': 'stat_list',
        'Stat Grid': 'stat_grid',
      },
    },
    stats: {
      description: 'Stats list',
      control: 'array',
    },
  },
};

export const StatsDark = StatsTemplate.bind({});
StatsDark.args = {
  title: 'All the pieces you need to kickstart your site',
  eyebrow: 'Eyebrow',
  media: '<img src="https://picsum.photos/1600/900" class="img-fluid rounded" alt="Placeholder" />',
  body: '<p>Lorem ipsum dolor sit amet consectetur adipiscing, elit platea sem eu vehicula consequat, venenatis auctor pellentesque metus commodo. Tempus fermentum natoque hac curae aliquet vehicula justo felis vel, sed nascetur senectus etiam posuere convallis integer euismod eleifend curabitur, penatibus torquent mattis vitae ligula conubia platea phasellus.</p>',
  modifier: 'dark bg-dark text-light',
  stats: [
    {
      media: '<span class="material-symbols-outlined">rocket_launch</span>',
      heading: 'Lorem ipsum dolor sit amet consectetur',
      body: 'Diam suscipit ligula velit integer inceptos sociis, lobortis cursus luctus mollis per etiam iaculis.',
      modifier: ''
    },
    {
      media: '<span class="material-symbols-outlined">forest</span>',
      heading: 'Dictumst primis leo gravida eros nulla mattis',
      body: 'Himenaeos erat natoque gravida tempus nisl curabitur ut praesent, orci porttitor luctus hendrerit habitasse facilisis.',
      modifier: ''
    },
    {
      media: '<span class="material-symbols-outlined">linked_services</span>',
      heading: 'Id nulla facilisis cras torquent',
      body: 'Volutpat nascetur libero neque pulvinar primis accumsan taciti id.',
      modifier: ''
    },
    {
      media: '<span class="material-symbols-outlined">timer_10_alt_1</span>',
      heading: 'Mollis erat litora velit pharetra ante',
      body: 'Fermentum tellus elementum porta euismod lectus condimentum hendrerit blandit interdum.',
      modifier: ''
    }
  ]
};

export const StatsList = StatsTemplate.bind({});
StatsList.args = {
  title: 'Heading text',
  eyebrow: '',
  media: '',
  body: '',
  modifier: 'stat_list',
  button: {
    url: '#',
    modifier: 'btn-primary has-icon',
    text: 'primary Button',
    icon: 'arrow_right_alt',
  },
  stats: [
    {
      media: '<span class="material-symbols-outlined">rocket_launch</span>',
      heading: 'Lorem ipsum dolor sit amet consectetur',
      body: 'Diam suscipit ligula velit integer inceptos sociis, lobortis cursus luctus mollis per etiam iaculis.',
      modifier: 'col-md-6 col-xl-3'
    },
    {
      media: '<span class="material-symbols-outlined">forest</span>',
      heading: 'Dictumst primis leo gravida eros nulla mattis',
      body: 'Himenaeos erat natoque gravida tempus nisl curabitur ut praesent, orci porttitor luctus hendrerit habitasse facilisis.',
      modifier: 'col-md-6 col-xl-3'
    },
    {
      media: '<span class="material-symbols-outlined">linked_services</span>',
      heading: 'Id nulla facilisis cras torquent',
      body: 'Volutpat nascetur libero neque pulvinar primis accumsan taciti id.',
      modifier: 'col-md-6 col-xl-3'
    },
    {
      media: '<span class="material-symbols-outlined">timer_10_alt_1</span>',
      heading: 'Mollis erat litora velit pharetra ante',
      body: 'Fermentum tellus elementum porta euismod lectus condimentum hendrerit blandit interdum.',
      modifier: 'col-md-6 col-xl-3'
    }
  ]
};

export const StatsGrid = StatsTemplate.bind({});
StatsGrid.args = {
  title: 'All the pieces you need to kickstart your site',
  eyebrow: 'Eyebrow',
  media: '<img src="https://picsum.photos/1600/900" class="img-fluid rounded" alt="Placeholder" />',
  body: '<p>Lorem ipsum dolor sit amet consectetur adipiscing, elit platea sem eu vehicula consequat, venenatis auctor pellentesque metus commodo. Tempus fermentum natoque hac curae aliquet vehicula justo felis vel, sed nascetur senectus etiam posuere convallis integer euismod eleifend curabitur, penatibus torquent mattis vitae ligula conubia platea phasellus.</p>',
  modifier: 'stat_grid',
  stats: [
    {
      media: '<span class="material-symbols-outlined">rocket_launch</span>',
      heading: 'Lorem ipsum dolor sit amet consectetur',
      body: 'Diam suscipit ligula velit integer inceptos sociis, lobortis cursus luctus mollis per etiam iaculis.',
      modifier: ''
    },
    {
      media: '<img src="/images/custom-icon.svg" alt="" typeof="foaf:Image" class="img-fluid">',
      heading: 'Dictumst primis leo gravida eros nulla mattis',
      body: 'Himenaeos erat natoque gravida tempus nisl curabitur ut praesent, orci porttitor luctus hendrerit habitasse facilisis.',
      modifier: ''
    },
    {
      media: '<span class="material-symbols-outlined">linked_services</span>',
      heading: 'Id nulla facilisis cras torquent',
      body: 'Volutpat nascetur libero neque pulvinar primis accumsan taciti id.',
      modifier: ''
    },
    {
      media: '<span class="material-symbols-outlined">timer_10_alt_1</span>',
      heading: 'Mollis erat litora velit pharetra ante',
      body: 'Fermentum tellus elementum porta euismod lectus condimentum hendrerit blandit interdum.',
      modifier: ''
    }
  ]
};
