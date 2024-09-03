@extends('pdf.layout.main')

@section('content')
  <div style="text-align: center;">
    <h2 style="margin: 0;">PEMERINTAH KOTA SURABAYA</h2>
    <h3 style="margin: 0;">KECAMATAN GUBENG</h3>
    <h3 style="margin: 0;">DESA KEBONSARI</h3>
    <p style="margin: 0; font-size: 11px;">Alamat: Jl. Airlangga No. 4, Kebonsari, Kec. Gubeng, Kota Surabaya, Jawa Timur 60286</p>
    <hr style="border: 1px solid black; margin-top: 5px;">
  </div>

  <div style="text-align: center; margin-top: 8px;">
    <h3 style="margin: 0; text-decoration: underline">SURAT PENGANTAR PINDAH DATANG PENDUDUK</h3>
    <p style="margin: 0; margin-top: 6px;">Nomor: {{ $record->kode_surat }}</p>
  </div>

  <div style="margin-top: 16px;">
    <p style="margin: 0; margin-bottom: 12px;">Yang bertanda tangan di bawah ini, menerangkan bahwa:</p>

    <table style="width: 100%; border-collapse: collapse;">
      <tr>
        <td style="width: 148px;">NIK</td>
        <td style="width: 12px;">:</td>
        <td>{{ $record->resident->nik }}</td>
      </tr>
      <tr>
        <td>Nama</td>
        <td>:</td>
        <td>{{ $record->resident->nama }}</td>
      </tr>
      <tr>
        <td>Tempat/Tanggal Lahir</td>
        <td>:</td>
        <td>{{ $record->ttl }}</td>
      </tr>
      <tr>
        <td>Jenis Kelamin</td>
        <td>:</td>
        <td>{{ $record->resident->jenis_kelamin }}</td>
      </tr>
      <tr>
        <td>Pekerjaan</td>
        <td>:</td>
        <td>{{ $record->resident->pekerjaan }}</td>
      </tr>
      <tr>
        <td>Alamat Sekarang</td>
        <td>:</td>
        <td>{{ $record->alamat_asal }}</td>
      </tr>
      <tr>
        <td>Alamat Tujuan</td>
        <td>:</td>
        <td>{{ $record->alamat_tujuan }}</td>
      </tr>
      <tr>
        <td>Jumlah Keluarga Pindah</td>
        <td>:</td>
        <td>{{ $record->jumlah_keluarga }} orang</td>
      </tr>
    </table>
  </div>

  <div style="margin-top: 12px;">
    <p style="margin: 0;">Adapun permohonan pindah datang penduduk tersebut di atas, telah disetujui dan diterima. Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
  </div>


  <div style="margin-top: 42px; text-align: right;">
    <p style="margin: 0;">Kebonsari, {{ $data['now'] }}</p>
    <p style="margin: 0">a/n Kepala Desa Kebonsari</p>
    <p style="margin: 0; margin-top: 46px;">Wardaya Rajasa, S.E.</p>
  </div>
@endsection
