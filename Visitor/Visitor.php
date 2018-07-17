<?php

namespace DesignPatterns\Behavioral;

/**
 * Basic interface for all visitors, declares a set of visiting methods.
 * Visitors should be aware about of all classes of documents
 */
interface Visitor
{
    public function visitTemplate(Template $template);

    public function visitReport(Report $report);
}

/**
 * Concrete visitor, extends documents by export function in JSON.
 * We should have an implementation for all concrete document classes
 */
class JSONExportVisitor implements Visitor
{
    public function visitTemplate(Template $template)
    {
        return json_encode($template->header . $template->title . $template->content . $template->footer);
    }

    public function visitReport(Report $report)
    {
        return json_encode($report->title . $report->diagram . $report->content);
    }
}

/**
 * Concrete visitor, extends documents by export function in XML.
 * Contains implementation of XML export for Template and Report classes
 */
class XMLExportVisitor implements Visitor
{

    public function visitTemplate(Template $template)
    {
        $xml = new \SimpleXMLElement('<xml/>');
        $xml->addChild('header', $template->header)
            ->addChild('title', $template->title)
            ->addChild('content', $template->content)
            ->addChild('footer', $template->footer);

        return $xml->asXML();
    }

    public function visitReport(Report $report)
    {
        $xml = new \SimpleXMLElement('<xml/>');
        $xml->addChild('title', $report->title)
            ->addChild('diagram', $report->diagram)
            ->addChild('content', $report->content);

        return $xml->asXML();
    }
}

/**
 * Declares an accept() method that takes the base visitor interface as an argument
 */
interface Visitable
{
    public function accept(Visitor $visitor);
}

/**
 * Base class for all documents.
 * Document hierarchy should only know about the basic interface of visitors
 */
abstract class Document implements Visitable
{
    public $title;
    public $content;

    public function __construct(string $title, string $content)
    {
        $this->title = $title;
        $this->content = $content;
    }
}

/**
 * Concrete documents should implement the accept() method in such a way
 * that it calls the visitor's method corresponding to the document's class
 */
class Template extends Document
{
    public $header = '{header}';
    public $footer = '{footer}';

    public function accept(Visitor $visitor)
    {
        return $visitor->visitTemplate($this);
    }
}

class Report extends Document implements Visitable
{
    public $diagram = ' {diagram} ';

    public function accept(Visitor $visitor)
    {
        return $visitor->visitReport($this);
    }
}

# Client code example
$report = new Report('report_title', 'report_content');
$template = new Template('template_title', ' template_content');

echo $report->accept(new JSONExportVisitor()) . PHP_EOL;
echo $report->accept(new XMLExportVisitor()) . PHP_EOL;
echo $template->accept(new JSONExportVisitor()) . PHP_EOL;

/* Output:
"report_title {diagram} report_content"
<?xml version="1.0" <xml><title>report_title<diagram>..
"{header}template_title template_content{footer}" */