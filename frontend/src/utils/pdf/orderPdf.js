import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import { getProductImageBase64, getBase64ImageFromUrl } from '../image';
import logoUrl from '../../assets/logo_2.png';

export const generateOrderPDF = async (order, details) => {
  try {
    const doc = new jsPDF();
    let currentY = 10;

    // --- TOP HEADER ---
    let logoB64 = null;
    try {
      logoB64 = await getBase64ImageFromUrl(logoUrl);
    } catch (e) {
      console.warn("Could not load logo for PDF", e);
    }

    autoTable(doc, {
      startY: currentY,
      theme: 'plain',
      body: [
        ['', 'REQUERIMIENTO DE PEDIDO', '']
      ],
      columnStyles: {
        0: { cellWidth: 50 },
        1: { cellWidth: 'auto', halign: 'center', valign: 'middle', fontSize: 13, fontStyle: 'bold' },
        2: { cellWidth: 50 }
      },
      didDrawCell: function (data) {
        if (data.section === 'body' && data.column.index === 0 && logoB64) {
          doc.addImage(logoB64, 'PNG', data.cell.x, data.cell.y, 45, 15);
        }
        if (data.section === 'body' && data.column.index === 2) {
          autoTable(doc, {
            startY: data.cell.y,
            margin: { left: data.cell.x },
            tableWidth: 50,
            theme: 'grid',
            body: [
              ['Código: PD-FOR-011'],
              ['Versión: 001'],
              ['F. Aprob.: 10/01/2022']
            ],
            styles: { fontSize: 8, cellPadding: 1.5, textColor: [0, 0, 0], lineColor: [200, 200, 200] }
          });
        }
      }
    });

    currentY = Math.max(doc.lastAutoTable.finalY, 25) + 10;

    // PEDIDO TITLE
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text(`PEDIDO Nro : ${order.codigo}`, 105, currentY, { align: 'center' });
    currentY += 5;

    // --- GENERAL DETAILS TABLE ---
    let imgB64 = null;
    if (order.imagen_alt || order.imagen) {
      try {
        imgB64 = await getProductImageBase64(order.imagen_alt || order.imagen);
      } catch (e) {
        console.warn("Could not load image for PDF", e);
      }
    }

    autoTable(doc, {
      startY: currentY,
      theme: 'grid',
      styles: { fontSize: 9, textColor: [0, 0, 0], lineColor: [200, 200, 200] },
      columnStyles: {
        0: { cellWidth: 40, fontStyle: 'normal' },
        1: { cellWidth: 'auto' }
      },
      body: [
        ['Fecha de entrega:', order.fecha_entrega ? new Date(order.fecha_entrega).toLocaleDateString('es-PE') : '-'],
        ['Cliente:', order.name || '-'],
        ['Tiempo de Entrega:', `${!isNaN(parseInt(order.dias_restantes)) ? parseInt(order.dias_restantes) : '-'}`],
        [{ content: (order.nombre_modelo || order.producto || '').toUpperCase(), colSpan: 2 }],
        [{ content: '', colSpan: 2, styles: { minCellHeight: imgB64 ? 80 : 10 } }]
      ],
      didDrawCell: function (data) {
        if (data.row.index === 4 && imgB64) {
          const imgWidth = 60;
          const imgHeight = 70;
          const x = data.cell.x + (data.cell.width - imgWidth) / 2;
          const y = data.cell.y + 5;
          doc.addImage(imgB64, 'JPEG', x, y, imgWidth, imgHeight);
        }
      }
    });

    currentY = doc.lastAutoTable.finalY + 5;

    // --- QUANTITIES TABLE ---
    const head = [
      [{ content: 'Modelo', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
      { content: 'Color', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
      { content: 'Cantidades por Talla', colSpan: 14, styles: { halign: 'center' } }],
      ['2', '4', '6', '8', '10', '12', '14', '16', 'S', 'M', 'L', 'XL', 'XXL', 'Total']
    ];

    const body = [];
    details.forEach(d => {
      const q = [d._2, d._4, d._6, d._8, d._10, d._12, d._14, d._16, d.s, d.m, d.l, d.xl, d.xxl, d.total];
      body.push([d.modelo || '', d.color || '', ...q.map(v => v || '')]);

      const pTotal = parseInt(d.p2 || 0) + parseInt(d.p4 || 0) + parseInt(d.p6 || 0) + parseInt(d.p8 || 0) +
        parseInt(d.p10 || 0) + parseInt(d.p12 || 0) + parseInt(d.p14 || 0) + parseInt(d.p16 || 0) +
        parseInt(d.ps || 0) + parseInt(d.pm || 0) + parseInt(d.pl || 0) + parseInt(d.pxl || 0) + parseInt(d.pxxl || 0);

      if (pTotal > 0 || (d.ptotal && parseInt(d.ptotal) > 0)) {
        body.push([{ content: 'PRODUCIDOS', colSpan: 2, styles: { fontStyle: 'bold' } },
        d.p2 || '', d.p4 || '', d.p6 || '', d.p8 || '', d.p10 || '', d.p12 || '', d.p14 || '', d.p16 || '',
        d.ps || '', d.pm || '', d.pl || '', d.pxl || '', d.pxxl || '', pTotal || d.ptotal || '']);
      }
    });

    autoTable(doc, {
      startY: currentY,
      head,
      body,
      theme: 'grid',
      styles: { fontSize: 8, textColor: [0, 0, 0], lineColor: [200, 200, 200], halign: 'center' },
      headStyles: { fillColor: [255, 255, 255], textColor: [0, 0, 0], halign: 'center', fontStyle: 'bold' },
      columnStyles: { 0: { cellWidth: 25, halign: 'left' }, 1: { cellWidth: 35, halign: 'left' } }
    });

    currentY = doc.lastAutoTable.finalY + 5;

    // --- COMMENTS TABLE ---
    autoTable(doc, {
      startY: currentY,
      theme: 'grid',
      styles: { fontSize: 9, textColor: [0, 0, 0], lineColor: [200, 200, 200] },
      body: [
        [`Comentarios: GUIA DE REMISION REMITENTE: ${order.guia_remision || '-'}`]
      ]
    });

    doc.save(`Pedido_${order.codigo}.pdf`);
  } catch (e) {
    console.error(e);
    alert('Error al generar el PDF del pedido');
  }
};
