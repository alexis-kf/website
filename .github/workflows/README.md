# Code Review Workflows

This directory contains GitHub Actions workflows for automated code review on Pull Requests.

## Available Workflows

### 1. `code-review.yml` - Basic Code Review
A simple workflow that runs essential code quality checks on every PR.

**Checks included:**
- PHP Lint
- Drupal Check
- Code Standards (PHPCS)
- Security Check
- Composer Validation
- Twig Template Check
- ESLint (if applicable)

### 2. `code-review-advanced.yml` - Advanced Code Review
A comprehensive workflow with detailed reporting and additional checks.

**Additional features:**
- Detailed step-by-step reporting
- PR comments with results
- Sensitive data detection
- Deprecated function checking
- Better error handling
- Caching for faster builds

## Configuration

### Environment Variables
You can customize the workflows by setting these environment variables in your repository settings:

- `PHP_VERSION`: PHP version to use (default: 8.2)
- `NODE_VERSION`: Node.js version to use (default: 18)

### Branches
The workflows run on these branches by default:
- `main`
- `develop` 
- `master`

### Triggers
- Pull Request events
- Push events to protected branches

## Customization

### Adding Custom Checks
To add custom checks, modify the workflow files and add new steps:

```yaml
- name: Custom Check
  run: |
    echo "Running custom check..."
    # Your custom check logic here
```

### Modifying Check Paths
The workflows check these directories by default:
- `web/modules/custom/`
- `web/profiles/custom/`
- `web/themes/custom/`

### Ignored Paths
These paths are automatically ignored:
- `vendor/`
- `web/core/`
- `web/modules/contrib/`
- `web/themes/contrib/`
- `web/profiles/contrib/`
- `node_modules/`

## Dependencies

The workflows require these tools to be available in `composer.json`:

```json
{
  "require-dev": {
    "drupal/coder": "^3.0",
    "mglaman/drupal-check": "^1.0",
    "enlightn/security-checker": "^1.10"
  }
}
```

## Troubleshooting

### Common Issues

1. **PHPCS not found**: Ensure `drupal/coder` is installed via Composer
2. **Drupal Check fails**: Check that `mglaman/drupal-check` is installed
3. **Security Check fails**: Verify `enlightn/security-checker` is available
4. **ESLint fails**: Make sure your theme has a `package.json` with lint scripts

### Debug Mode
To enable debug output, add this to your workflow:

```yaml
env:
  ACTIONS_STEP_DEBUG: true
```

## Security Considerations

- The workflows run in a controlled environment
- No sensitive data is logged or stored
- All dependencies are installed from trusted sources
- Security checks are performed on dependencies

## Contributing

To improve these workflows:

1. Test changes in a fork first
2. Follow the existing code style
3. Add appropriate documentation
4. Ensure all checks pass before submitting

## Support

For issues with these workflows:
1. Check the Actions tab for detailed logs
2. Review the troubleshooting section
3. Create an issue with detailed error information 